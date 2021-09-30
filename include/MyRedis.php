<?php

/**
 * redis操作类，网页模式用
 */
class MyRedis extends Redis{
    public $config = array(
        'server' => "127.0.0.1",
        'port' => 6379,
        'timeout' => 3,
    );
    private $isClosed = false;
    private $isConnected = false;
    private $dbIndex = 0;
    private $lastTimestamp = 0;
    protected $isMulti = false;
    private $debug = false;
    private static $instance;

    public static function getInstance(){
        $class = get_called_class();
        if (self::$instance==null){
            self::$instance = array();
        }
        if(!array_key_exists($class, self::$instance)){
            self::$instance["$class"] = new static();
        }
        return self::$instance["$class"];
    }

    public function __construct(){
        parent::__construct();
    }

    public function resetconfig($config){
        $this->config = $config;
    }

    public function redisSuperFunc($func, $params){
        if(!is_array($params)){
            $params = [$params];
        }
        $result = call_user_func_array(array($this, $func), $params);
        if($this->debug==true){
            $this->logDebug(":redis_command:{$func}, command_params:".json_encode($params), ', command_result:'.json_encode($result), $this->debug);
        }
        return $result;
    }

    public function init() {
        $now = time();
        if($this->lastTimestamp + $this->config['timeout'] < $now ){
            //时间超过timeout， 重新连接
            $this->lastTimestamp = $now;
            $this->isConnected = false;
        }
        if(!$this->isConnected){
            $connected = false;
            $haslogederror = false ;
            try{
                $connected=$this->connect($this->config['server'],
                    $this->config['port'],
                    $this->config['timeout']
                );
                if($this->dbIndex>0){
                    $this->select($this->dbIndex);
                }
                $retry = 1; //只尝试一次
                while($retry-->0){
                    if($this->ping()==""){
                        $this->logError("Ping error.");
                    }else{
                        break;
                    }
                }
            }catch(Exception $e){
                $this->logError( "Cannot connect to redis server | errmsg:".$e->getMessage() );
                $haslogederror = true ;

                ob_start();
                debug_print_backtrace();
                $message = ob_get_clean();
                $this->logError($message);
            }
            if(!$connected){
                if( $haslogederror == false){
                    $this->logError( "connect timeout.");
                }
            }else{
                $this->isConnected = true;
                $this->isClosed=false;
            }
        }
        return $this;
    }

    public function setDb($index=0){
        $this->dbIndex = $index;
        return $this;
    }

    public function getDb(){
        return $this->dbIndex;
    }

    public function __destruct() {
        $this->close();
    }

    public function multi($type = null){
        $this->isMulti = true;
        if($type === null){
            return parent::multi();
        }else{
            return parent::multi($type);
        }
    }

    public function exec(){
        $this->isMulti = false;
        return parent::exec();
    }

    public function isMulti(){
        return $this->isMulti;
    }

    public function close(){
        if(!$this->isClosed && $this->isConnected){
            parent::close();
            $this->isClosed = true;
            $this->isConnected = false;
        }
    }

    public function logError($err = ""){
        $connstr = "server:{$this->config['server']}:{$this->config['port']} | instance:".get_called_class() ;
        file_put_contents(__DIR__.'/../log/redis_error.log', "[".date("Y-m-d H:i:s")."] | {$connstr} | connect error: {$err}".PHP_EOL, FILE_APPEND);
    }

    public function logDebug($called_detail, $return_result){
        $connstr = "server:{$this->config['server']}:{$this->config['port']} | instance:".get_called_class() ;
        file_put_contents(__DIR__.'/../log/redis_debug.log', "[".date("Y-m-d H:i:s")."] | {$connstr} | called detail: {$called_detail} | return result: {$return_result}".PHP_EOL, FILE_APPEND);
    }

}