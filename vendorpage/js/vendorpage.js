//image slider
document.addEventListener('DOMContentLoaded', function () {
    var mainSplide = new Splide('#main-carousel', {
        type: 'fade',
        pagination: false,
        arrows: true,
    }).mount();

    var thumbnailSplide = new Splide('#thumbnail-carousel', {
        fixedWidth: 100,
        fixedHeight: 60,
        gap: 10,
        rewind: true,
        pagination: false,
        isNavigation: true,
    }).mount();

    mainSplide.sync(thumbnailSplide);
});

document.addEventListener('DOMContentLoaded', function () {
    const arrowContainer = document.querySelector('#thumbnail-carousel .splide__arrows');
    const lastThumbnail = document.querySelector('#thumbnail-carousel .splide__list .splide__slide:last-child');

    if (arrowContainer && lastThumbnail) {
        const thumbnailWidth = lastThumbnail.offsetWidth;
        const thumbnailRightPosition = lastThumbnail.getBoundingClientRect().right;
        const containerRightPosition = arrowContainer.getBoundingClientRect().right;

        const arrowRightPosition = containerRightPosition - thumbnailRightPosition + thumbnailWidth;
        arrowContainer.style.right = arrowRightPosition + 'px';
    }
});

// drop down function
const drop_btn = document.querySelector(".menu");
const menu_wrapper = document.querySelector(".menu_wrapper");
drop_btn.onclick = (()=>{
menu_wrapper.classList.toggle("show");
});

// star rating function
var rating = parseFloat(document.querySelector('.rating_label_primary').textContent.split('/')[0]);
var starContainer = document.getElementById('star-container');

for (var i = 1; i <= 5; i++) {
  if (i <= Math.floor(rating)) {
    starContainer.innerHTML += "<i class='bx bxs-star'></i>";
  } else if (i - rating < 1 && i - rating > 0) {
    starContainer.innerHTML += "<i class='bx bxs-star-half'></i>";
  } else {
    starContainer.innerHTML += "<i class='bx bx-star' style='color:#ccc;'></i>";
  }
}

// Review and Comment Submit Function
let totalReviews = 0;
let totalStars = 0;
let starCounts = {
    1: 0,
    2: 0,
    3: 0,
    4: 0,
    5: 0
};
let selectedRating = 0;

function updateStarContainer(containerSelector, ratingSelector) {
    let rating = parseFloat(document.querySelector(ratingSelector).textContent.split('/')[0]);
    let starContainer = document.querySelector(containerSelector);
    
    starContainer.innerHTML = "";

    for (let i = 1; i <= 5; i++) {
        if (i <= Math.floor(rating)) {
            starContainer.innerHTML += "<i class='bx bxs-star'></i>";
        } else if (i - rating < 1 && i - rating > 0) {
            starContainer.innerHTML += "<i class='bx bxs-star-half'></i>";
        } else {
            starContainer.innerHTML += "<i class='bx bx-star' style='color:grey;'></i>";
        }
    }
}

function updateUI() {
  let averageRating = 0;
  if (totalReviews > 0) {
      averageRating = (totalStars / totalReviews).toFixed(1);
  }

  $("#average_rating").text(averageRating);
  $(".rating_label_primary").text(`${averageRating}/5`);
  $(".rating_label_secondary").text(`(${totalReviews})`);
  $("#total_review").text(totalReviews);

    // Update progress bars and counts
    for (let i = 1; i <= 5; i++) {
        const progress = (starCounts[i] / totalReviews * 100).toFixed(1);
        const idMap = {
            1: 'one',
            2: 'two',
            3: 'three',
            4: 'four',
            5: 'five'
        };
        $(`#${idMap[i]}_star_progress`).css("width", `${progress}%`);
        $(`#total_${idMap[i]}_star_review`).text(starCounts[i]);
    }
    updateStarContainer('.mb-3', '#average_rating');
    updateStarContainer('#star-container', '.rating_label_primary');
}

// Event listener for selecting stars in the comment section
$(".submit_star").on('click', function() {
    console.log("Selected Rating after click:", selectedRating);
    selectedRating = $(this).data("rating");
    $(".submit_star").each(function() {
        $(this).removeClass('text-warning').addClass('star-light');
        if ($(this).data("rating") <= selectedRating) {
            $(this).removeClass('star-light').addClass('text-warning');
        }
    });
});

// Event listener for the submit button
$(document).ready(function() {
    let username;
    let canSubmitReview = false; 
    let vendorpage_id;

    const user_id = getCookie('user_id');
    const vendor_name = decodeURIComponent(window.location.pathname.split('/').pop().replace('.html', ''));

    $.when(
        $.ajax({
          url: '/api/get_username.php',
          method: 'GET',
          data: { user_id: user_id },
        }),
        $.ajax({
          url: '/api/get_vendorpage_id.php',
          method: 'GET',
          data: { vendor_name: vendor_name },
        })
        ).then(function(usernameResponse, vendorResponse) {
            let usernameData = JSON.parse(usernameResponse[0]);
            let vendorData = JSON.parse(vendorResponse[0]);
            
            console.log('Parsed Username Data:', usernameData);
            console.log('Parsed Vendor Data:', vendorData);
            
            username = usernameData.username;
            vendorpage_id = vendorData.vendorpage_id;
            
            return $.ajax({
                url: '/api/get_review.php',
                method: 'GET',
                data: { vendorpage_id: vendorpage_id },
            });
        }).then(function(reviewsResponse) {
            const reviews = JSON.parse(reviewsResponse);
            totalReviews = reviews.length;
            totalStars = 0;
            starCounts = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };
            reviews.forEach(function(review) {
                addReviewToPage(review.username, review.comment, review.rating, review.review_date);

                totalStars += review.rating;
                starCounts[review.rating]++;
            });

            updateUI();

        }).then(function() {
            canSubmitReview = true;
        }).then(function() {
            $("#save_review").click(function() {
                console.log("Selected Rating before AJAX call:", selectedRating);
                console.log("Save review button clicked");
                console.log("Current username: ", username);
                if (!canSubmitReview) {
                    alert("Still loading, please wait...");
                    return;
                }
                const userReview = $("#user_review").val();
                const datetime = new Date().toLocaleString();
                
                if (username && userReview && vendorpage_id && selectedRating !== 0) {
                    $.ajax({
                        url: "/api/rating.php",
                        method: "POST",
                        data: {
                            vendorpage_id: vendorpage_id,
                            user_id: user_id,
                            rating: selectedRating,
                            comment: userReview
                        },
                        success: function(response) {
                            console.log("AJAX call succeeded, response received:", response);
                            alert("Review submitted successfully.");
                        }, error: function(jqXHR, textStatus, errorThrown) {
                            console.log("Error:", textStatus, errorThrown);
                        }
                    });  
                }
                // Update counts and UI
                totalReviews++;
                totalStars += selectedRating;
                starCounts[selectedRating]++;
                updateUI();
                
                addReviewToPage(username, userReview, selectedRating, datetime);
                // Clear the form
                $("#user_review").val("");
                selectedRating = 0;
                $(".submit_star").removeClass('text-warning').addClass('star-light');     
            });
        });
});

function generateStarsHTML(rating) {
    let starsHTML = '';
    for(let star = 1; star <= 5; star++) {
        let class_name = '';
        if(rating >= star) {
            class_name = 'bx bxs-star';
        } else {
            class_name = 'bx bx-star';
        }
        starsHTML += `<i class='${class_name}'></i>`;
    }
    return starsHTML;
}

function addReviewToPage(username, userReview, selectedRating, datetime) {
    const starsHTML = generateStarsHTML(selectedRating);
    const reviewHTML = `                
        <div class="user_comment">
            <div class="user">
                <div class="card-header"><h2>${username}</h2></div>
                <div class="card-body">
                    <div class="star">
                    ${starsHTML}
                    </div>
                    ${userReview}
                </div>
                <div class="card-end" id="datatime">
                    ${datetime}
                </div>
            </div>
        </div>
    `;
    $('#review_content').append(reviewHTML);
}

// Opening Hours Function
document.addEventListener("DOMContentLoaded", function() {
    var openingHoursDataElement = document.getElementById('opening_hours_data');
    var openingHoursData = openingHoursDataElement.textContent;
    var openingHours = JSON.parse(openingHoursData);
  
    var currentDay = new Date().getDay();
    var days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    var todayHours = openingHours[days[currentDay]];
  
    var openingHour = todayHours.open === 'Closed' ? -1 : parseInt(todayHours.open.split(':')[0]);
    var closingHour = todayHours.close === 'Closed' ? -1 : parseInt(todayHours.close.split(':')[0]);
  
    var currentHour = new Date().getHours();
  
    var timeElement = document.getElementById('time');
    var additionalInfo = document.createElement('span');
  
    if (openingHour === -1) {
      for (var i = 1; i <= 7; i++) {
        var nextDayIndex = (currentDay + i) % 7;
        var nextDayHours = openingHours[days[nextDayIndex]];
        if (nextDayHours.open !== 'Closed') {
          additionalInfo.textContent = ' Opens ' + days[nextDayIndex] + ' ' + nextDayHours.open;
          break;
        }
      }
      timeElement.textContent = 'Closed';
      timeElement.style.color = 'rgba(217,48,37,1.00)'
    } else if (currentHour >= openingHour && currentHour < closingHour) {
      if (currentHour >= closingHour - 1) {
        timeElement.textContent = 'Closed Soon';
        additionalInfo.textContent = ' ' + todayHours.close;
      } else {
        timeElement.textContent = 'Open Now';
        timeElement.style.color = 'rgba(24,128,56,1.00)';
      }
    } else {
      if (currentHour >= openingHour - 1 && currentHour < openingHour) {
        timeElement.textContent = 'Open Soon';
        additionalInfo.textContent = ' ' + todayHours.open;
      } else {
        timeElement.textContent = 'Closed';
        additionalInfo.textContent = ' Opens ' + todayHours.open;
      }
    }
  
    timeElement.parentNode.appendChild(additionalInfo);
    additionalInfo.style.color = 'black';
});

document.addEventListener("DOMContentLoaded", function() {
    const menuScroll = document.getElementById("menu-scroll");
    let isDown = false;
    let startX;
    let scrollLeft;

    menuScroll.addEventListener('mousedown', (e) => {
        isDown = true;
        menuScroll.classList.add('grabbing');
        startX = e.pageX - menuScroll.offsetLeft;
        scrollLeft = menuScroll.scrollLeft;
    });

    menuScroll.addEventListener('mouseleave', () => {
        isDown = false;
        menuScroll.classList.remove('grabbing');
    });

    menuScroll.addEventListener('mouseup', () => {
        isDown = false;
        menuScroll.classList.remove('grabbing');
    });

    menuScroll.addEventListener('mousemove', (e) => {
        if(!isDown) return;
        e.preventDefault();
        const x = e.pageX - menuScroll.offsetLeft;
        const walk = (x - startX) * 2;
        menuScroll.scrollLeft = scrollLeft - walk;
    });
});

$(document).ready(function() {
    var urlPath = window.location.pathname;
    var vendorPageName = decodeURIComponent(urlPath.split('/').pop().replace('.html', ''));
    var user_id, vendor_id, vendorpage_id;
    
    $.ajax({
        url: '/api/get_vendorpage_id.php',
        type: 'GET',
        data: { 'vendor_name': vendorPageName },
        success: function(response) {
            var parsedResponse = JSON.parse(response);
            if (parsedResponse.status === "success") {
                vendorpage_id = parsedResponse.vendorpage_id;
            } else {
                console.log("Failed to get vendorpage_id");
            }
        },
        error: function(xhr, status, error) {
            console.log("Error: ", error);
        }
    }).done(function() {
        setTimeout(function() {
            var cookies = document.cookie.split('; ');
            for (var i = 0; i < cookies.length; i++) {
                var cookiePair = cookies[i].split('=');
                if (cookiePair[0] === 'vendor_id') {
                    vendor_id = cookiePair[1];
                    break;
                }
            }
            for (var i = 0; i < cookies.length; i++) {
                var cookiePair = cookies[i].split('=');
                if (cookiePair[0] === 'user_id') {
                    user_id = cookiePair[1];
                    break;
                }
            }                
            if ((user_id || vendor_id) && vendorpage_id) {
                $.ajax({
                    url: '/api/record_page_view.php',
                    type: 'POST',
                    data: { 'user_id': user_id, 'vendor_id': vendor_id, 'vendorpage_id': vendorpage_id },
                    success: function(response) {
                        console.log("Success:", response);
                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                    }
                });
            } else {

                console.log("Vendor ID or vendorpage ID not found.");
            }
        }, 15000);  // 15 seconds
    });
});