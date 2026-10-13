"use strict";

document.addEventListener("DOMContentLoaded", function () {

    /* ===== HEADER ===== */
    const header = document.querySelector('header');
    const menuBtn = document.querySelector('#menu-btn');
    const userBtn = document.querySelector('#user-btn');

    if (header) {
        window.addEventListener('scroll', () => {
            header.classList.toggle('scroll', window.scrollY > 0);
        });
    }

    if (menuBtn) {
        menuBtn.onclick = () =>
            document.querySelector('.navbar')?.classList.toggle('active');
    }

    if (userBtn) {
        userBtn.onclick = () =>
            document.querySelector('.user-box')?.classList.toggle('active');
    }

    /* ===== HOME SLIDER AUTO ===== */
    const slider = document.querySelector('.slider');
    const homeSlides = document.querySelectorAll('.slider_slider');
    let currentIndex = 0;

    if (slider && homeSlides.length > 0) {
        const slideWidth = slider.clientWidth;

        function autoSlide() {
            currentIndex = (currentIndex + 1) % homeSlides.length;

            slider.scrollTo({
                left: slideWidth * currentIndex,
                behavior: 'smooth'
            });
        }

        let sliderInterval = setInterval(autoSlide, 2000);

        // Pause khi hover / touch
        slider.addEventListener('mouseenter', () => clearInterval(sliderInterval));
        slider.addEventListener('mouseleave', () => {
            sliderInterval = setInterval(autoSlide, 2000);
        });

        slider.addEventListener('touchstart', () => clearInterval(sliderInterval));
        slider.addEventListener('touchend', () => {
            sliderInterval = setInterval(autoSlide, 2000);
        });

        // Fix resize
        window.addEventListener('resize', () => {
            slider.scrollTo({
                left: slider.clientWidth * currentIndex,
                behavior: 'auto'
            });
        });
    }

    /* ===== TESTIMONIAL SLIDER ===== */
    const testimonialSlides = document.querySelectorAll('.testimonial-item');
    const nextBtn = document.querySelector('.right-arrow');
    const prevBtn = document.querySelector('.left-arrow');
    let testimonialIndex = 0;

    if (testimonialSlides.length > 0) {
        function showSlide(i) {
            testimonialSlides.forEach(slide => slide.classList.remove('active'));
            testimonialSlides[i].classList.add('active');
        }

        const nextSlide = () => {
            testimonialIndex = (testimonialIndex + 1) % testimonialSlides.length;
            showSlide(testimonialIndex);
        };

        const prevSlide = () => {
            testimonialIndex =
                (testimonialIndex - 1 + testimonialSlides.length) % testimonialSlides.length;
            showSlide(testimonialIndex);
        };

        if (nextBtn) nextBtn.onclick = nextSlide;
        if (prevBtn) prevBtn.onclick = prevSlide;

        setInterval(nextSlide, 2000);
    }

});

const header = document.querySelector ('header');

function fixedNavbar(){
header.classList.toggle('scrolled', window. pageYOffset > 0)
}
fixedNavbar();
window.addEventListener ('scroll', fixedNavbar);

let menu = document.querySelector('#menu-btn');

menu. addEventListener('click', function(){
let nav = document. querySelector (' .navbar'); 
	nav.classList.toggle('active');
})

let userBtn = document.querySelector ('#user-btn');

userBtn.addEventListener ('click', function(){
let userBox = document. querySelector(' .profile-detail'); 
	userBox.classList.toggle('active');
})
document.querySelectorAll(".add-cart").forEach(btn => {
    btn.addEventListener("click", async (e) => {

        e.preventDefault();

        const id = btn.dataset.id;
        const qty = btn.closest("form").querySelector(".qty").value;

        const formData = new FormData();
        formData.append("product_id", id);
        formData.append("qty", qty);

        const res = await fetch("api/api_cart.php", {
            method: "POST",
            body: formData,
            credentials: "include"
        });

        const data = await res.json();

        alert(data.message);

        if (data.status === "success") {

            // 🔥 update badge mà KHÔNG cần sửa header
            const badge = document.querySelector(".cart-count");

            if (badge) {
                badge.textContent = data.cart_count;
            }
        }
    });
});
/* ===== ADD PRODUCT (ADMIN) ===== */
const form = document.querySelector(".form-container form");

if (form) {
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        // xác định nút bấm (publish hay draft)
        const activeBtn = document.activeElement.name;

        if (activeBtn === "publish") {
            formData.append("type", "publish");
        } else {
            formData.append("type", "draft");
        }

        try {
            const res = await fetch("api/add_product.php", {
                method: "POST",
                body: formData,
                credentials: "include"
            });

            const data = await res.json();

            if (data.status === "success") {
                alert(data.message);
                form.reset();
            } else {
                alert(data.message);
            }

        } catch (err) {
            console.error(err);
            alert("Lỗi kết nối API");
        }
    });
}