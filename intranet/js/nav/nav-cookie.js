// use with cookies

//i dont use it yet, becaus of some buggs

const cookiesNav = document.cookie.split('=');

var cookieMAIN = ";domain=;path=/";

if (cookiesNav[2] == "false") {
    document.cookie = "navOpen=false" + cookieMAIN; 

    document.querySelectorAll("#navtext").forEach(function (elem) {
        elem.classList.remove('textClose');
    });

    document.querySelectorAll('#singlenav').forEach(function (elem) {
        elem.classList.remove('navClose');
        elem.classList.add('navOpen')
    });

    document.getElementById("sidenav").classList.remove("navCloseSide");
    document.getElementById("main").classList.remove("navCloseMain");

    document.getElementById("sidenav").classList.add("navOpenSide");
    document.getElementById("main").classList.add("navOpenMain");

    clicked = false;

  } else { 
    document.cookie = "navOpen=true" + cookieMAIN; 
    //remove text close
    document.querySelectorAll('#navtext').forEach(function (elem) {
        elem.classList.add('textClose');
    });

    //add icons
    document.querySelectorAll('#singlenav').forEach(function (elem) {
        elem.classList.add('navClose');
        elem.classList.remove('navOpen')
    });

    //document.getElementById("sidenav").style.width = "4rem";
    //document.getElementById("main").style.marginLeft= "4rem";

    document.getElementById("sidenav").classList.add("navCloseSide");
    document.getElementById("main").classList.add("navCloseMain");

    document.getElementById("sidenav").classList.remove("navOpenSide");
    document.getElementById("main").classList.remove("navOpenMain");

    clicked = true;
  }