document.addEventListener('DOMContentLoaded', function() {
  const container = document.querySelector(".container");
  const pwShowHide = document.querySelectorAll(".showHidePw");
  const pwFields = document.querySelectorAll(".password");
  const signUp = document.querySelector(".signup-link");
  const login = document.querySelectorAll(".login-link"); 
  const vendorRegister = document.querySelector(".vendor-signup-link");
  const loginLinks = document.querySelectorAll(".login-link");
  const emailVerify = document.querySelector('.signup_verify')

  //   js code to show/hide password and change icon
  pwShowHide.forEach(eyeIcon =>{
    eyeIcon.addEventListener("click", ()=>{
      pwFields.forEach(pwField =>{
        if(pwField.type ==="password"){
          pwField.type = "text";
          
          pwShowHide.forEach(icon =>{
            icon.classList.replace("uil-eye-slash", "uil-eye");
          })
        }else{
          pwField.type = "password";
          
          pwShowHide.forEach(icon =>{
            icon.classList.replace("uil-eye", "uil-eye-slash");
          })
        }
      }) 
    })
  })
 
  //function to switch forms
  function switchForm(formClass) {
    // Hide all forms
    document.querySelectorAll('.form').forEach(form => {
      form.classList.remove('active');
    });
    
    // Show the selected form
    document.querySelector(formClass).classList.add('active');
  }
  
  signUp.addEventListener("click", () => {
    switchForm('.signup');
  });
  
  loginLinks.forEach(loginLink => {
    loginLink.addEventListener("click", () => {
      switchForm('.login');
    });
  });

  emailVerify.addEventListener("click",() =>{
    switchForm('.email_verify')
  })
  
  vendorRegister.addEventListener("click", () => {
    switchForm('.vendor');
  });

  switchForm('.login');
});

document.getElementById("loginButton").addEventListener("click", function() {
  let userId = "someUserId";
  document.cookie = "user_id=" + userId + ";path=/";
});