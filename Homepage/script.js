document.addEventListener('DOMContentLoaded', () => {
    fetchMenuItems();

    // Event listener for "Add to Cart" and quantity buttons
    document.body.addEventListener('click', (e) => {
        // Handle "Add to Cart" button clicks
        if (e.target.classList.contains('add-to-cart-btn')) {
            handleAddToCart(e.target);
        }

        // Handle quantity button clicks
        if (e.target.classList.contains('quantity-btn')) {
            handleQuantityChange(e.target);
        }
    });
});

function filterMenu() {
    fetchMenuItems();
}

async function fetchMenuItems() {
    const categoryFilter = document.getElementById('categoryFilter');
    const category = categoryFilter.value;
    const menuGrid = document.getElementById('menuGrid');
    const loader = document.getElementById('loader');

    menuGrid.innerHTML = '';
    loader.style.display = 'block';

    try {
        const response = await fetch(`get_menu_items.php?category=${category}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const items = await response.json();

        if (items.error) {
            menuGrid.innerHTML = `<p class="error">${items.error}</p>`;
            return;
        }

        if (items.length === 0) {
            menuGrid.innerHTML = '<p class="no-items">No items found in this category.</p>';
            return;
        }

        items.forEach(item => {
            // --- UPDATED HTML TEMPLATE STRING ---
            const menuItemHtml = `
                <div class="menu-item">
                    <img src="${item.image}" alt="${item.name}" />
                    <div class="menu-item-content">
                        <h3>${item.name}</h3>
                        <p class="category">${item.category}</p>
                        <p class="price">Rs. ${parseFloat(item.price).toFixed(2)}</p>
                        <div class="add-to-cart-controls">
                            <div class="quantity-control">
                                <button class="quantity-btn minus-btn">-</button>
                                <span class="quantity-value">1</span>
                                <button class="quantity-btn plus-btn">+</button>
                            </div>
                            <button class="add-to-cart-btn" data-id="${item.id}">Add to Cart</button>
                        </div>
                    </div>
                </div>
            `;
            // --- END OF UPDATED SECTION ---
            menuGrid.innerHTML += menuItemHtml;
        });

    } catch (e) {
        console.error("Failed to fetch menu items:", e);
        menuGrid.innerHTML = '<p class="error">Failed to load menu items. Please try again later.</p>';
    } finally {
        loader.style.display = 'none';
    }
}

// New function to handle the quantity changes
function handleQuantityChange(button) {
    const quantityControl = button.closest('.quantity-control');
    const quantitySpan = quantityControl.querySelector('.quantity-value');
    let quantity = parseInt(quantitySpan.textContent, 10);

    if (button.classList.contains('plus-btn')) {
        quantity++;
    } else if (button.classList.contains('minus-btn')) {
        if (quantity > 1) { // Prevent quantity from going below 1
            quantity--;
        }
    }
    quantitySpan.textContent = quantity;
}

async function handleAddToCart(button) {
    const itemId = button.getAttribute('data-id');
    // --- UPDATED QUANTITY RETRIEVAL ---
    const quantityControl = button.parentNode.querySelector('.quantity-control');
    const quantitySpan = quantityControl.querySelector('.quantity-value');
    const quantity = parseInt(quantitySpan.textContent, 10);
    // --- END OF UPDATED SECTION ---

    if (quantity < 1) {
        alert("Quantity must be at least 1.");
        return;
    }

    try {
        const response = await fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ item_id: itemId, quantity: quantity }),
        });

        const result = await response.json();
        
        if (result.success) {
            alert("Item added to cart successfully!");
        } else {
            alert("Error adding item to cart: " + result.error);
        }

    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again later.');
    }
}

function confirmLogout() {
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = 'logout.php';
    }
}