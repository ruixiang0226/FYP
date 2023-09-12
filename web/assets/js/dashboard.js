let menuicn = document.querySelector(".menuicn");
    let nav = document.querySelector(".navcontainer");
    menuicn.addEventListener("click", () => {
        nav.classList.toggle("navclose");
    })

document.getElementById('dashboardNav').addEventListener('click', function() {
    switchContent('dashboardContent');
});

document.getElementById('vendorApplyNav').addEventListener('click', function() {
    switchContent('vendorApplicationContent');
});
    
document.getElementById('userAccountNav').addEventListener('click', function() {
    switchContent('userAccountContent');
});

document.getElementById('vendorAccountNav').addEventListener('click', function() {
    switchContent('vendorAccountContent');
});
    
function switchContent(id) {
    let sections = document.querySelectorAll('.content-section');
    for (let section of sections) {
        section.classList.remove('active');
    }
    document.getElementById(id).classList.add('active');
}
  