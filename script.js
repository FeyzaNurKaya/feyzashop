document.addEventListener("DOMContentLoaded", function() {
    // Mevcut sayfanın menüdeki bağlantısını vurgula
    const currentPageUrl = window.location.href;
    const menuLinks = document.querySelectorAll("#navbar a");

    menuLinks.forEach(link => {
        if (link.href === currentPageUrl) {
            link.classList.add("active");
        }
    });

    const menuOpenBtn = document.querySelector("#bar");
    const menuCloseBtn = document.querySelector("#close");
    const navbarDom = document.querySelector("#navbar");

    if (menuOpenBtn) {
        menuOpenBtn.addEventListener("click", menuOpen);
    }
    
    if (menuCloseBtn) {
        menuCloseBtn.addEventListener("click", menuClose);
    }

    function menuOpen() {
        navbarDom.style.right = "0px";
    }

    function menuClose() {
        navbarDom.style.right = "-300px";
    }

    const MainImgDom = document.querySelector("#MainImg");
    const smallImgDom = document.getElementsByClassName("smallImg");

    const addToCartButtons = document.querySelectorAll('.add-to-cart');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', addToCart);
    });

    // Sepet array'ini oluştur
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Ürünü sepete ekleme fonksiyonu
function addToCart(productName, price) {
    // Ürünü sepet array'ine ekle
    cart.push({ name: productName, price: price });
    // Sepeti localStorage'a kaydet
    localStorage.setItem('cart', JSON.stringify(cart));
    alert('Ürün sepete eklendi!');
}

// Sepet sayfasını yükleme fonksiyonu
window.onload = function() {
    const cartContent = document.getElementById('cart-content');
    cart = JSON.parse(localStorage.getItem('cart')) || [];

    if (cart.length === 0) {
        cartContent.innerHTML = '<p>Sepetinizde ürün bulunmamaktadır.</p>';
    } else {
        let total = 0;
        let tableContent = `
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>ÜRÜN</th>
                        <th>BİRİM FİYAT</th>
                        <th>ADET</th>
                        <th>TOPLAMI</th>
                    </tr>
                </thead>
                <tbody>`;

        cart.forEach(item => {
            tableContent += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.price.toFixed(2)} TL</td>
                    <td>1 Adet</td>
                    <td>${item.price.toFixed(2)} TL</td>
                </tr>`;
            total += item.price;
        });

        tableContent += `
                </tbody>
            </table>
            <div class="cart-summary">
                <p>Sipariş Toplamı: <strong>${total.toFixed(2)} TL</strong></p>
                <p>Kargo: <strong>Ücretsiz</strong></p>
                <p>Toplam: <strong>${total.toFixed(2)} TL</strong></p>
                <button>Satın Al</button>
            </div>`;

        cartContent.innerHTML = tableContent;
    }
}

    
    // Kaydol formunu göstermek için
    document.getElementById('signup-form-link').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('signup-form').style.display = 'block';
    });

    // Giriş formunu göstermek için
    document.getElementById('login-form-link').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('signup-form').style.display = 'none';
        document.getElementById('login-form').style.display = 'block';
    });
	
function filterProducts(gender) {
    fetch(`/filter-products?gender=${gender}`)
        .then(response => response.json())
        .then(data => {
            const productsContainer = document.getElementById('products-container');
            productsContainer.innerHTML = '';

            data.products.forEach(product => {
                const productElement = `
                    <div class="product-item">
                        <img src="${product.image_url}" alt="${product.name}">
                        <h3>${product.name}</h3>
                        <p>${product.price} TL</p>
                    </div>`;
                productsContainer.innerHTML += productElement;
            });
        })
        .catch(error => console.error('Error fetching products:', error));
}

fetch('fetch_recommended_products.php')
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            console.error(data.error);
        } else {
            const container = document.getElementById('recommendedProducts');
            data.forEach(product => {
                const productDiv = document.createElement('div');
                productDiv.className = 'product';
                productDiv.innerHTML = `
                    <img src="${product.image}" alt="${product.name}">
                    <h3>${product.name}</h3>
                    <p>${product.price} TL</p>
                `;
                container.appendChild(productDiv);
            });
        }
    })
    .catch(error => console.error('Hata:', error));
});
