function menu(evt, menuCat) {
    //Declare all variables
    var i, menucontent, tablinks;

    //Get all elements with class="menucontent and hide them
    menucontent = document.getElementsByClassName("menucontent");
    for (i = 0; i < menucontent.length; i++) {
        menucontent[i].style.display = "none";
    }

    //Get all elements with class="menucontent" and remove class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace("active", "");
    }

    //SHow the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(menuCat).style.display = "block";
    evt.currentTarget.className += " active";
}


// Get the element by id="MainDisplay" and click on it
document.getElementById("MainDisplay").click();

//Set maximum date for finance report
datePickerId.max = new Date().toLocaleDateString('fr-ca');

const header = document.querySelector('header');
function fixedNavbar(){
    header.classList.toggle('scrolled', window.pageYOffset > 0)
}
fixedNavbar();
window.addEventListener('scroll', fixedNavbar);

let menu = document.querySelector('#menu-btn');
let userBtn = document.querySelector('#user-btn');

menu.addEventListener('click', function(){
    let nav = document.querySelector('.navbar');
    nav.classList.toggle('active');
})

userBtn.addEventListener('click', function(){
    let userBox = document.querySelector('.user-box');
    userBox.classList.toggle('active');
})


// Get the current date and time
var currentDate = new Date();
var currentDateTime = currentDate.getFullYear() + '-' + ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' + ('0' + currentDate.getDate()).slice(-2) + 'T' + ('0' + currentDate.getHours()).slice(-2) + ':' + ('0' + currentDate.getMinutes()).slice(-2);
