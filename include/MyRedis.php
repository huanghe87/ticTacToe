<?php
class MyRedis extends Redis{
    var $config = array(
        'server' => "127.0.0.1",
        'port' => 6379,
        'timeout' => 3,
    );
    private $is_closed = false;
    private $is_connected = false;
    private $db_index = 0;
    private $last_timestamp = 0;
    protected $isMulti = false;
    private $debug = false;
    public $is_redisproxy = false;

    private static $instance;
    public static function getInstance(){
        $class = get_called_class();
        if (self::$instance==null){
            self::$instance = array();
        }
        if (!array_key_exists($class, self::$instance) ){
            self::$instance["$class"] = new static();
        }
        return self::$instance["$class"];
    }

    function __construct(){
        parent::__construct();
    }

    function resetconfig($config){
        $this->config = $config;
    }

    /**
     * @author zhouhui
     * @since 2018-12-27
     * 为了方便记录每次redis执行的语句，方便调试
     * @param $func
     * @param $params
     */
    function redis_super_func($func, $params){
        if(!is_array($params)){
            $params = [$params];
        }
        $result = call_user_func_array(array($this, $func), $params);

        if($this->debug==true){
            $this->log_debug(":redis_command:{$func}, command_params:".json_encode($params), ', command_result:'.json_encode($result), $this->debug);
        }

        return $result;
    }

    function init() {
        $now = time();
        if ($this->last_timestamp + $this->config['timeout'] < $now ){
            //时间超过timeout， 重新连接
            $this->last_timestamp = $now;
            $this->is_connected = false;
        }

        if(!$this->is_connected){
            $connected = false;
            $haslogederror = false ;
            try {
                $connected=$this->connect($this->config['server'],
                    $this->config['port'],
                    $this->config['timeout']
                );
                if($this->db_index>0){
                    $this->select($this->db_index);
                }
                $retry = 1; //只尝试一次
                while($retry-->0){
                    if($this->ping()==""){
                        $this->log_error("Ping error.");
                    }else{
                        break;
                    }
                }
            } catch (Exception $e) {
                $this->log_error( "Cannot connect to redis server | errmsg:".$e->getMessage() );
                $haslogederror = true ;

                ob_start();
                debug_print_backtrace();
                $message = ob_get_clean();
                $this->log_error($message);
            }
            if( !$connected ){
                if( $haslogederror == false){
                    $this->log_error( "connect timeout.");
                }
            }else{
                $this->is_connected = true;
                $this->is_closed=false;
            }
        }
        return $this;
    }
    function set_db($index=0){
        $this->db_index = $index;
        return $this;
    }
    function get_db(){
        return $this->db_index;
    }
    function __destruct() {
        $this->close();
    }

    function multi($type = null)
    {
        $this->isMulti = true;
        if ($type === null)
        {
            return parent::multi();
        }
        else
        {
            return parent::multi($type);
        }
    }

    function exec()
    {
        $this->isMulti = false;
        return parent::exec();
    }

    function IsMulti()
    {
        return $this->isMulti;
    }

    function close(){
        if(!$this->is_closed && $this->is_connected){
            parent::close();
            $this->is_closed = true;
            $this->is_connected = false;
        }
    }
    //输出log
    function log_error($err = ""){
        $connstr = "server:{$this->config['server']}:{$this->config['port']} | instance:".get_called_class() ;
        file_put_contents(__DIR__.'/../log/redis_error.log', "[".date("Y-m-d H:i:s")."] | {$connstr} | connect error: {$err}".PHP_EOL, FILE_APPEND);
    }

    function log_debug($called_detail, $return_result){
        $connstr = "server:{$this->config['server']}:{$this->config['port']} | instance:".get_called_class() ;
        file_put_contents(__DIR__.'/../log/redis_debug.log', "[".date("Y-m-d H:i:s")."] | {$connstr} | called detail: {$called_detail} | return result: {$return_result}".PHP_EOL, FILE_APPEND);
    }

}