document.getElementById('profileNav').addEventListener('click', function() {
    switchContent('profile_content');
});

document.getElementById('reviewNav').addEventListener('click', function() {
    switchContent('review_content');
});

document.getElementById('historyNav').addEventListener('click', function() {
    switchContent('history_content');
});

document.getElementById('feedbackNav').addEventListener('click', function() {
    switchContent('feedback_content');
});
    
    
function switchContent(id) {
    let sections = document.querySelectorAll('.content-section');
    for (let section of sections) {
        section.classList.remove('active');
    }
    document.getElementById(id).classList.add('active');
}

const navBar = document.querySelector(".navbar")
        allLi = document.querySelectorAll("li");
  allLi.forEach((li) => {
     li.addEventListener("click" , e =>{
       e.preventDefault(); 
       navBar.querySelector(".active").classList.remove("active");
       li.classList.add("active");
     });
});

document.addEventListener("DOMContentLoaded", function() {
    var starContainers = document.querySelectorAll('.star-container');
    
    starContainers.forEach(function(starContainer) {
        var rating = starContainer.getAttribute('data-rating');
        for (var i = 1; i <= 5; i++) {
            if (i <= Math.floor(rating)) {
                starContainer.innerHTML += "<i class='bx bxs-star'></i>";
            } else if (i - rating < 1 && i - rating > 0) {
                starContainer.innerHTML += "<i class='bx bxs-star-half'></i>";
            } else {
                starContainer.innerHTML += "<i class='bx bx-star' style='color:#ccc;'></i>";
            }
        }
    });
});