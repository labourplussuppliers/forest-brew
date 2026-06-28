/*=========================================================
FROST & BREW
Main JavaScript
Version 1.0
=========================================================*/

document.addEventListener("DOMContentLoaded", function () {

    "use strict";

    /*=====================================================
    Preloader
    =====================================================*/

    const preloader = document.getElementById("preloader");

    if (preloader) {

        window.addEventListener("load", function () {

            preloader.classList.add("hide");

            setTimeout(() => {

                if (preloader.parentNode) {
                    preloader.parentNode.removeChild(preloader);
                }
            }, 500);

        });

        setTimeout(() => {
            if (preloader.parentNode && !preloader.classList.contains('hide')) {
                preloader.classList.add('hide');
                preloader.parentNode.removeChild(preloader);
            }
        }, 3000);

    }

    const mobileMenu = document.getElementById("mobileMenu");

    const mobileOffcanvas = mobileMenu ? bootstrap.Offcanvas.getOrCreateInstance(mobileMenu) : null;

    document.querySelectorAll(".offcanvas .nav-link, .offcanvas .btn").forEach(item => {

        item.addEventListener("click", function () {

            if (mobileOffcanvas) {

                mobileOffcanvas.hide();

            }

        });

    });

    /*=====================================================
    Sticky Navbar
    =====================================================*/

    const navbar = document.querySelector(".navbar");

    function stickyNavbar() {

        if (!navbar) return;

        if (window.scrollY > 80) {

            navbar.classList.add("scrolled");

        } else {

            navbar.classList.remove("scrolled");

        }

    }

    stickyNavbar();

    window.addEventListener("scroll", stickyNavbar);

    /*=====================================================
    Back To Top
    =====================================================*/

    const backToTop = document.getElementById("backToTop");

    if (backToTop) {

        window.addEventListener("scroll", function () {

            if (window.scrollY > 300) {

                backToTop.style.display = "flex";

            } else {

                backToTop.style.display = "none";

            }

        });

        backToTop.addEventListener("click", function () {

            window.scrollTo({

                top: 0,

                behavior: "smooth"

            });

        });

    }

    /*=====================================================
    Product Quantity
    =====================================================*/

    document.querySelectorAll(".quantity-box").forEach(box => {

        const minus = box.querySelector(".minus");

        const plus = box.querySelector(".plus");

        const input = box.querySelector("input");

        if (!minus || !plus || !input) return;

        minus.addEventListener("click", () => {

            let value = parseInt(input.value) || 1;

            if (value > 1) {

                input.value = value - 1;

            }

        });

        plus.addEventListener("click", () => {

            let value = parseInt(input.value) || 1;

            input.value = value + 1;

        });

    });

    /*=====================================================
    Bootstrap Tooltips
    =====================================================*/

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

    tooltipTriggerList.map(function (tooltipTriggerEl) {

        return new bootstrap.Tooltip(tooltipTriggerEl);

    });

    /*=====================================================
    Bootstrap Popovers
    =====================================================*/

    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));

    popoverTriggerList.map(function (popoverTriggerEl) {

        return new bootstrap.Popover(popoverTriggerEl);

    });

    /*=====================================================
    Fade Animation
    =====================================================*/

    const fadeElements = document.querySelectorAll(".fade-in");

    if ("IntersectionObserver" in window) {

        const observer = new IntersectionObserver(entries => {

            entries.forEach(entry => {

                if (entry.isIntersecting) {

                    entry.target.classList.add("show");

                }

            });

        });

        fadeElements.forEach(el => observer.observe(el));

    }

    /*=====================================================
    Search Auto Focus
    =====================================================*/

    const searchInput = document.querySelector(".search-input");

    if (searchInput) {

        document.addEventListener("keydown", function (e) {

            if (e.key === "/") {

                e.preventDefault();

                searchInput.focus();

            }

        });

    }

    /*=====================================================
    Confirm Delete
    =====================================================*/

    document.querySelectorAll(".confirm-delete").forEach(button => {

        button.addEventListener("click", function (e) {

            e.preventDefault();

            const url = this.getAttribute("href");

            Swal.fire({

                title: "Delete Item?",

                text: "This action cannot be undone.",

                icon: "warning",

                showCancelButton: true,

                confirmButtonColor: "#5C3D2E",

                cancelButtonColor: "#dc3545",

                confirmButtonText: "Delete"

            }).then((result) => {

                if (result.isConfirmed) {

                    window.location.href = url;

                }

            });

        });

    });

    /*=====================================================
    Newsletter
    =====================================================*/

    const newsletter = document.querySelector("#newsletterForm");

    if (newsletter) {

        newsletter.addEventListener("submit", function () {

            Swal.fire({

                icon: "success",

                title: "Subscribed",

                text: "Thank you for subscribing."

            });

        });

    }

});


/*=========================================================
Toast Notification
=========================================================*/

function showToast(icon, title) {

    Swal.fire({

        toast: true,

        position: "top-end",

        icon: icon,

        title: title,

        showConfirmButton: false,

        timer: 2500,

        timerProgressBar: true

    });

}


/*=========================================================
Currency Formatter
=========================================================*/

function formatCurrency(value) {

    return "Rs. " + Number(value).toLocaleString();

}


/*=========================================================
Loader
=========================================================*/

function showLoader() {

    document.body.classList.add("loading");

}

function hideLoader() {

    document.body.classList.remove("loading");

}


/*=========================================================
Copy Text
=========================================================*/

function copyText(text) {

    navigator.clipboard.writeText(text);

    showToast("success", "Copied Successfully");

}


/*=========================================================
Image Preview
=========================================================*/

function previewImage(input, target) {

    if (!input.files.length) return;

    const reader = new FileReader();

    reader.onload = function (e) {

        document.querySelector(target).src = e.target.result;

    }

    reader.readAsDataURL(input.files[0]);

}


/*=========================================================
Print Page
=========================================================*/

function printPage() {

    window.print();

}


/*=========================================================
Reload Page
=========================================================*/

function reloadPage() {

    location.reload();

}


/*=========================================================
AJAX Helper
=========================================================*/

async function postRequest(url, data = {}) {

    const response = await fetch(url, {

        method: "POST",

        headers: {

            "Content-Type": "application/json"

        },

        body: JSON.stringify(data)

    });

    return await response.json();

}
