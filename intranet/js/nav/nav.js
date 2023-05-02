var clicked = false;

function toggleNav(button) {
  if (clicked) {
    localStorage.setItem("nav", "open");

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
    
    document.getElementById("main").classList.add("transition");

    clicked = false;

  } else { 
    localStorage.setItem("nav", "close");
    //remove text close
    document.querySelectorAll('#navtext').forEach(function (elem) {
        elem.classList.add('textClose');
    });

    //add icons
    document.querySelectorAll('#singlenav').forEach(function (elem) {
        elem.classList.add('navClose');
        elem.classList.remove('navOpen')
    });

    document.getElementById("sidenav").classList.add("navCloseSide");
    document.getElementById("main").classList.add("navCloseMain");

    document.getElementById("sidenav").classList.remove("navOpenSide");
    document.getElementById("main").classList.remove("navOpenMain");
    
    document.getElementById("main").classList.add("transition");

    clicked = true;
  }
}