document.getElementById('showRegister').addEventListener('click', function(e) {
    e.preventDefault();
    document.querySelector('.login-form').style.opacity = '0';
    document.querySelector('.login-form').style.visibility = 'hidden';
    document.getElementById('registerForm').style.opacity = '1';
    document.getElementById('registerForm').style.visibility = 'visible';
});

document.getElementById('showLogin').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('registerForm').style.opacity = '0';
    document.getElementById('registerForm').style.visibility = 'hidden';
    document.querySelector('.login-form').style.opacity = '1';
    document.querySelector('.login-form').style.visibility = 'visible';
});