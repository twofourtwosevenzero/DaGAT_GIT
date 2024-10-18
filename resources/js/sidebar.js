let arrow = document.querySelectorAll(".arrow");
for (var i = 0; i < arrow.length; i++) {
  arrow[i].addEventListener("click", (e)=>{
    let arrowParent = e.target.parentElement.parentElement; //selecting main parent of arrow
    arrowParent.classList.toggle("showMenu");
  });
}
let sidebar = document.querySelector(".sidebar");
let logo = document.querySelector(".logo-details");
logo.addEventListener("click", ()=>{
  sidebar.classList.toggle("close");
});

document.addEventListener('DOMContentLoaded', (event) => {
let currentPage = window.location.pathname.split('/').pop();

switch(currentPage) {
  case 'dashboard.blade.php':
    document.getElementById('dashboard').classList.add('active');
    break;
  case 'documents.html':
    document.getElementById('documents').classList.add('active');
    break;
  case 'activitylog.html':
    document.getElementById('activitylog').classList.add('active');
    break;
  case 'digitallog.html':
    document.getElementById('digitallog').classList.add('active');
    break;
  case 'usermanagement.html':
    document.getElementById('usermanagement').classList.add('active');
    break;
  case 'aboutus.html':
    document.getElementById('aboutus').classList.add('active');
    break;
  case 'signin.html':
    document.getElementById('signin').classList.add('active');
    break;
  // Add other cases for your pages
  default:
    break;
}
});