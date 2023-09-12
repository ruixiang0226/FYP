// Searching Function
$(document).ready(function(){
  let timer;

  function resetMargins() {
    $('.vendor').removeClass('first-in-row');  // Remove the class from all vendors
    $('.vendor:visible').each(function(index) {
      if (index % 4 === 0) {
        $(this).addClass('first-in-row');  // Add the class to every 4th visible element
      }
    });
  }

  $("input[type='search']").on("input", function() {
    clearTimeout(timer);
    timer = setTimeout(() => {
      var keyword = $(this).val().toLowerCase();
      var foundInFrontend = false;

      // Frontend search
      $(".vendor").each(function() {
        var vendor = $(this);
        var vendorName = vendor.find("h2").text().toLowerCase().replace(/\s+/g, '');
        var foodType = vendor.find(".food_type p").text().toLowerCase().split(', ');

        var keywordMatch = foodType.some(function(type) {
          return type.includes(keyword) && !(keyword === "halal" && type === "non-halal");
        });
        
        if (vendorName.includes(keyword) || keywordMatch) {
          vendor.show();
          foundInFrontend = true;
        } else {
          vendor.hide();
        }
      }); 
      
      // Backend search
      if (!foundInFrontend && keyword.length > 2) {
        $.ajax({
          url: "/api/get_address.php",
          method: "GET",
          data: { query: keyword },
          success: function(response) {
            updateSearchResults(response);
          },
          error: function(error) {
            console.error("Error fetching data", error);
          }
        });
      }
      resetMargins();
    }, 300);
  });
  resetMargins();
});

function updateSearchResults(data) {
  const parsedData = typeof data === 'string' ? JSON.parse(data) : data;
  const matchingVendorIds = new Set(parsedData.map(item => item.id));

  $(".vendor").each(function() {
    const vendor = $(this);
    const vendorId = Number(vendor.attr("id").replace("vendorpage_", ""));

    if (matchingVendorIds.has(vendorId)) {
      console.log("Showing vendor: ", vendorId);
      vendor.show();
    } else {
      console.log("Hiding vendor: ", vendorId);
      vendor.hide();
    }
  });
}

// Automatic Image Slider Function
let currentIndex = 0;

function slideImages() {
  const slideWrapper = document.querySelector('.slide-wrapper');
  const slides = document.querySelectorAll('.slide');
  const totalSlides = slides.length;

  if (currentIndex === totalSlides - 1) {
    slideWrapper.style.transition = 'none';
    slideWrapper.style.transform = 'translateX(0%)';
    slideWrapper.offsetHeight;
    slideWrapper.style.transition = '';
    currentIndex = 0;
  } else {
    currentIndex++;
  }

  slideWrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
}

setInterval(slideImages, 3000);

// Rank abour rating
let completedVendors = [];

$(".vendor").each(function(index, element) {
  const idAttr = $(this).attr('id');
  const vendorpage_id = idAttr.split('_')[1];

  $.ajax({
    url: '/api/get_review.php',
    method: 'GET',
    data: { vendorpage_id: vendorpage_id },
  }).then(function(reviewsResponse) {
    const reviews = JSON.parse(reviewsResponse);

    let totalReviews = reviews.length;
    let totalRating = 0;
    reviews.forEach(function(review) {
      totalRating += review.rating;
    });
    let averageRating = (totalReviews > 0) ? (totalRating / totalReviews).toFixed(1) : 0;

    function updateStars(starContainer, rating) {
      starContainer.empty();
      for (var i = 1; i <= 5; i++) {
        var starElement = $('<i></i>');
        starElement.addClass('bx');
        if (i <= Math.floor(rating)) {
          starElement.addClass('bxs-star');
        } else if (i - rating < 1 && i - rating > 0) {
          starElement.addClass('bxs-star-half');
        } else {
          starElement.addClass('bx-star');
        }
        starContainer.append(starElement);
      }
    }

    $(this).attr('data-stars', averageRating);
    $(this).attr('data-rating', totalReviews);
    $(this).find(".rating_label_primary").text(`${averageRating}/5`);
    $(this).find(".rating_label_secondary").text(`(${totalReviews})`);
    updateStars($(this).find('.star-container'), averageRating);

    completedVendors.push(this);

    if (completedVendors.length === $(".vendor").length) {
      sortVendors();
    }

  }.bind(this));
});

function sortVendors() {
  const vendors = Array.from(document.querySelectorAll('.vendor'));
  const sortedVendors = vendors.sort((a, b) => {
    const ratingA = parseFloat(a.getAttribute('data-rating'));
    const ratingB = parseFloat(b.getAttribute('data-rating'));
    const starsA = parseInt(a.getAttribute('data-stars'));
    const starsB = parseInt(b.getAttribute('data-stars'));

    const scoreA = ratingA + (starsA / 100);
    const scoreB = ratingB + (starsB / 100);

    return scoreB - scoreA;
  });

  const vendorContainer = $("#vendorContainer");
  vendorContainer.empty();
  sortedVendors.forEach(vendor => {
    vendorContainer.append(vendor);
  });
}

$(document).ready(function(){
  $('.next').click(function(){
    $('.pagination').find('.page_number.active').next().
    addClass('active');
    $('.pagination').find('.page_number.active').prev().
    removeClass('active');
  })
  $('.prev').click(function(){
    $('.pagination').find('.page_number.active').prev().
    addClass('active');
    $('.pagination').find('.page_number.active').next().
    removeClass('active');
  })
})

document.addEventListener("DOMContentLoaded", function() {
  let currentPage = 1;
  const vendorsPerPage = 12;
  const vendorContainer = document.getElementById("vendorContainer");
  const vendors = Array.from(vendorContainer.querySelectorAll('.vendor'));
  const paginationContainer = document.querySelector('.pagination');

  function displayPage(page) {
      // Hide all vendors first
      vendors.forEach(vendor => vendor.style.display = 'none');

      // Calculate start and end index
      const start = (page - 1) * vendorsPerPage;
      const end = start + vendorsPerPage;

      // Show vendors for the current page
      vendors.slice(start, end).forEach(vendor => vendor.style.display = 'block');

      // Update pagination
      updatePagination();
  }

  function updatePagination() {
      // Clear previous pagination
      paginationContainer.innerHTML = '';

      // Calculate total pages
      const totalPages = Math.ceil(vendors.length / vendorsPerPage);

      // Create "Prev" button
      const prev = document.createElement('li');
      prev.innerHTML = '<a href="#" class="prev">< Prev</a>';
      prev.addEventListener('click', (event) => {
        event.preventDefault();
        goToPage(currentPage - 1);
      });
      
      paginationContainer.appendChild(prev);
      // Create page numbers
      for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement('li');
        li.className = 'page_number';
        li.innerHTML = `<a href="#">${i}</a>`;
        if (i === currentPage) {
        li.classList.add('active');
      }
      li.addEventListener('click', (event) => {
        event.preventDefault();
        goToPage(i);
    });
    paginationContainer.appendChild(li);
  }
  
  // Create "Next" button
  const next = document.createElement('li');
  next.innerHTML = '<a href="#" class="next">Next ></a>';
  next.addEventListener('click', (event) => {
    event.preventDefault();
    goToPage(currentPage + 1);
  });
  
  paginationContainer.appendChild(next);
}

function goToPage(page) {
  if (page < 1 || page > Math.ceil(vendors.length / vendorsPerPage)) {
    return;
  }
  currentPage = page;
  displayPage(currentPage);
}
displayPage(currentPage);
});


