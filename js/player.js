function playbtu() {
    var oyd = document.getElementById('ydmc');
    if (yaudio.paused) {
        yaudio.play();
        yaudio.volume = 0.5;
        oyd.className = 'iconfont icon-music';
    } else {
        yaudio.pause();
        yaudio.volume = 0.5;
        oyd.className = 'iconfont icon-bofang';
    }
}

function next() {
    var oyd = document.getElementById('ydmc');
    if (a == musicArr.length - 1) {
        a = 0
    } else {
        a = a + 1
    }
    var sj = musicArr[a];
    yaudio.src = sj.mp3;
    yaudio.play();
    var autopause = 0;
    yaudio.volume = 0.5;
    oyd.className = 'iconfont icon-music';
}

yaudio.addEventListener('ended', function() {
    next();
}, false);


yaudio.addEventListener("error", function() {
    next();
}, false);
