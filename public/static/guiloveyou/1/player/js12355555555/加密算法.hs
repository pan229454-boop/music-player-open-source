function netmusic() {
    Lrc.load();
    
    const song = playerinfo.Sheetlist[albumId].songs[songId];
    const timestamp = Math.floor(Date.now() / 3600000);
    const sign = generateSign(song.id, timestamp);

    if (song.type == "local") {
        audio.src = song.url;
    } else {
        audio.src = api + "/api/musicUrl?songId=" + song.id + 
                    "&type=" + song.type + 
                    "&id=" + key + 
                    "&sign=" + sign;
    }

    $songName.html("<span title=\"" + song.name + "\">" + LimitStr(song.name) + "</span>");
    $artist.html("<span title=\"" + song.artist + "\">" + LimitStr(song.artist) + "</span>");
    $songAlbum.html("<span title=\"" + song.album +  "\">" + LimitStr(song.album) + "</span>");

    var d = new Image();
    d.src = song.cover;
    $cover.addClass("changing");
    d.onload = function() {
        $cover.removeClass("changing");
        $.ajax({
            url: api + "/api/mainColor",
            type: "GET",
            dataType: "script",
            data: {
                url: d.src,
                id: key
            },
            success: function() {
                playerColor();
            },
            error: function() {
                var a = "0,0,0";
                playerColor();
            }
        });
    };

    d.onerror = function() {
        d.src = "https://q1.qlogo.cn/g?b=qq&nk=2963246343&s=140";
        $(".cover", $player).html("<img src='" + d.src + "'>");
        setTimeout(function() {
            Tips.show("专辑图片获取失败");
        }, 4000);
    };

    $(".cover", $player).html("<img src='" + d.src + "'>");
    
    if (first == 1) {
        first = 2;
        if (playerinfo.autoPlayer == 1 && ($.cookie("auto_playre") == null)) {
            startPlay();
        }
    } else {
        startPlay();
    }

    $(window).scroll(function() {
        var a = $(this).scrollTop();
        var b = $(window.document).height();
        var c = $(this).height();
        if (a + c == b) {
            zdyc = false;
            if (hasgeci && ycgeci) {
                $songFrom4.hide();
                $("#Lrc").addClass("hide");
                $("#Ksc").addClass("hidePlayer");
                if (hasLrc || hasKsc) {
                    Tips.show("歌词自动隐藏");
                }
            }
        } else {
            zdyc = true;
            if (hasgeci && ycgeci) {
                if (hasLrc || hasKsc) {
                    $songFrom4.show();
                }
                $("#Lrc").removeClass("hide");
                $("#Ksc").removeClass("hidePlayer");
            }
        }
    });
}

function generateSign(songId, timestamp) {
    const key = "guiybfqyyds";
    let baseString = songId + ":" + timestamp + ":" + key;
    let charArray = [];

    for (let i = 0; i < baseString.length; i++) {
        let charCode = baseString.charCodeAt(i);
        charCode = (charCode + (i % 7) * 3) ^ (i % 5);
        charArray.push(charCode);
    }

    let hexString = charArray.map(num => num.toString(16).padStart(2, '0')).join('');
    return hexString.split('').reverse().join('');
}