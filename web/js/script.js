const cells = document.querySelectorAll('.cell');
for (let i = 0; i < cells.length; i++) {
    cells[i].addEventListener('click', turnClick, false);
}

function turnClick(square) {
    location.href = '/ticTacToe/index.php?pos='+square.target.id;
}

function startGame() {
    location.href = '/ticTacToe/index.php?type=1';
}