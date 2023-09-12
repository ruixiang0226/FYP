// Select food type function
  const selectBtn = document.querySelector(".select-btn");
  const items = document.querySelectorAll(".item");
  
  selectBtn.addEventListener("click", () => {
    selectBtn.classList.toggle("open");
  });
  
  items.forEach(item => {
    item.addEventListener("click", () => {
        item.classList.toggle("checked");

        let checked = document.querySelectorAll(".checked");
        let btnText = document.querySelector(".btn-text");

        if (checked && checked.length > 0) {
            btnText.innerText = `${checked.length} Selected`;
        } else {
            btnText.innerText = "Select Type of Food";
        }
    });
});

function toggleFoodType(value) {
  var inputElement = document.querySelector(`input[name="food_type[]"][value="${value}"]`);
  if (inputElement) {
      inputElement.checked = !inputElement.checked;
  } else {
      console.error(`Element not found for value: ${value}`);
  }
}

const circles = document.querySelectorAll(".circle");
const progressBar = document.querySelector(".indicator");
const buttons = document.querySelectorAll("button");

let currentStep = 1;

const updateSteps = (e) => {
    currentStep = e.target.id === "next" ? ++currentStep : --currentStep;

    circles.forEach((circle, index) => {
        circle.classList[`${index < currentStep ? "add" : "remove"}`]("active");
    });

    progressBar.style.width = `${((currentStep - 1) / (circles.length - 1)) * 100}%`;

    if (currentStep === circles.length) {
        buttons[1].disabled = true;
    } else if (currentStep === 1) {
        buttons[0].disabled = true;
    } else {
        buttons.forEach((button) => (button.disabled = false));
    }
};


// Main Image Function //
const selectImage = document.querySelector('.select-image');
const inputFile = document.querySelector('#file');
const imgArea = document.querySelector('.img-area');

selectImage.addEventListener('click', function (e) {
    e.preventDefault();
    inputFile.click();
});

inputFile.addEventListener('change', function () {
    const image = this.files[0];
    if (image && /^image\/\w+$/.test(image.type) && image.size < 2000000) {
        const reader = new FileReader();
        reader.onload = () => {
            const allImg = imgArea.querySelectorAll('img');
            allImg.forEach(item => item.remove());
            const imgUrl = reader.result;
            const img = document.createElement('img');
            img.src = imgUrl;
            imgArea.appendChild(img);
            imgArea.classList.add('active');
            imgArea.dataset.img = image.name;
        };
        reader.readAsDataURL(image);
    } else {
        alert("Please select a valid image file less than 2MB");
    }
});


document.addEventListener("DOMContentLoaded", () => {
  const upload = document.querySelector(".upload-file");
  const fileInput = document.querySelector(".file-input");
  const uploadedArea = document.querySelector(".uploaded-area");
  let filesData = [];

  upload.addEventListener("click", () => {
    fileInput.click();
  });

  fileInput.addEventListener("change", function() {
    const files = this.files;

    Array.from(files).forEach((file) => {
      if (filesData.find((existingFile) => existingFile.name === file.name)) {
        return;  // Skip if file already exists
      }

      // Store the file data
      filesData.push(file);

      // Create list item to show file name and remove-icon
      const listItem = document.createElement("li");
      listItem.classList.add("row");
      listItem.innerHTML = `
        <div class="content upload">
          <div class="details">
            <span>${file.name}</span>  <!-- Show file name here -->
          </div>
        </div>
        <i class="remove-icon uil uil-times" data-name="${file.name}"></i>
      `;

      uploadedArea.appendChild(listItem);

      // Event listener for the remove-icon
      const removeIcon = listItem.querySelector('.remove-icon');
      removeIcon.addEventListener("click", function() {
        const name = this.getAttribute("data-name");
        filesData = filesData.filter((data) => data.name !== name);
        uploadedArea.removeChild(listItem);
      });
    });
  });
  console.log("Total number of files:", filesData.length);
});




// Menu Form Function (adding more menu form) //
document.addEventListener('DOMContentLoaded', function() {

  const menuImgWrappers = document.querySelectorAll('.menu-img-wrapper');

  menuImgWrappers.forEach((imgWrapper, index) => {

    imgWrapper.addEventListener('click', function() {
      document.querySelectorAll('.menu-img')[index].click();
    });

    document.querySelectorAll('.menu-img')[index].addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {

          // Remove existing preview image if any
          const existingPreview = imgWrapper.querySelector('img');
          if (existingPreview) {
            imgWrapper.removeChild(existingPreview);
          }

          // Create a new image element
          const img = document.createElement('img');
          img.src = e.target.result;
          img.alt = "Image Preview";

          // Append new image to wrapper
          imgWrapper.appendChild(img);

          // Optional: Store the Data URL in data-img attribute
          imgWrapper.setAttribute('data-img', e.target.result);
        };
        reader.readAsDataURL(file);
      }
    });
  });
});

// Mutiple Step Form (Prev,Next) //
document.addEventListener('DOMContentLoaded', function() {
  const forms = document.querySelectorAll('.form');
  let currentFormIndex = 0;

  const prevButton = document.querySelector('.prev');
  const nextButton = document.querySelector('.next');
  const menuPrev = document.getElementById('menuPrev');

  forms[currentFormIndex].classList.add('active');

  nextButton.addEventListener('click', function() {
    if (currentFormIndex < forms.length - 1) {
      forms[currentFormIndex].classList.remove('active');
      currentFormIndex++;
      forms[currentFormIndex].classList.add('active');
    }
  });

  prevButton.addEventListener('click', function() {
    if (currentFormIndex > 0) {
      forms[currentFormIndex].classList.remove('active');
      currentFormIndex--;
      forms[currentFormIndex].classList.add('active');
    }
  });
  menuPrev.addEventListener('click', function() {
    if (currentFormIndex > 0) {
      forms[currentFormIndex].classList.remove('active');
      currentFormIndex--;
      forms[currentFormIndex].classList.add('active');
    }
  });
});

  
  
    
