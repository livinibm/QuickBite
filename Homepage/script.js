// Sample menu data
const menuItems = [
    {
        id: 1,
        name: "Margherita Pizza",
        category: "Pizza",
        price: 12.99,
        image: "https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?w=400&h=300&fit=crop"
    },
    {
        id: 2,
        name: "Pepperoni Pizza",
        category: "Pizza",
        price: 14.99,
        image: "https://images.unsplash.com/photo-1628840042765-356cda07504e?w=400&h=300&fit=crop"
    },
    {
        id: 3,
        name: "BBQ Chicken Pizza",
        category: "Pizza",
        price: 16.99,
        image: "https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop"
    },
    {
        id: 4,
        name: "Classic Burger",
        category: "Burgers",
        price: 9.99,
        image: "https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop"
    },
    {
        id: 5,
        name: "Cheese Burger",
        category: "Burgers",
        price: 11.99,
        image: "https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=400&h=300&fit=crop"
    },
    {
        id: 6,
        name: "Bacon Burger",
        category: "Burgers",
        price: 13.99,
        image: "https://images.unsplash.com/photo-1553979459-d2229ba7433b?w=400&h=300&fit=crop"
    },
    {
        id: 7,
        name: "Chicken Caesar Salad",
        category: "Salads",
        price: 8.99,
        image: "https://images.unsplash.com/photo-1546793665-c74683f339c1?w=400&h=300&fit=crop"
    },
    {
        id: 8,
        name: "Greek Salad",
        category: "Salads",
        price: 7.99,
        image: "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=300&fit=crop"
    },
    {
        id: 9,
        name: "Garden Salad",
        category: "Salads",
        price: 6.99,
        image: "https://images.unsplash.com/photo-1511690743698-d9d85f2fbf38?w=400&h=300&fit=crop"
    },
    {
        id: 10,
        name: "Chicken Wings",
        category: "Appetizers",
        price: 8.99,
        image: "https://images.unsplash.com/photo-1567620832904-9d64bcd6c381?w=400&h=300&fit=crop"
    },
    {
        id: 11,
        name: "Mozzarella Sticks",
        category: "Appetizers",
        price: 6.99,
        image: "https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop"
    },
    {
        id: 12,
        name: "French Fries",
        category: "Appetizers",
        price: 4.99,
        image: "https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400&h=300&fit=crop"
    },
    {
        id: 13,
        name: "Chocolate Cake",
        category: "Desserts",
        price: 5.99,
        image: "https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400&h=300&fit=crop"
    },
    {
        id: 14,
        name: "Ice Cream Sundae",
        category: "Desserts",
        price: 4.99,
        image: "https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=400&h=300&fit=crop"
    },
    {
        id: 15,
        name: "Apple Pie",
        category: "Desserts",
        price: 6.99,
        image: "https://images.unsplash.com/photo-1535920527002-b35e3f412d4f?w=400&h=300&fit=crop"
    },
    {
        id: 16,
        name: "Coca Cola",
        category: "Beverages",
        price: 2.99,
        image: "https://images.unsplash.com/photo-1629203851122-3726ecdf080e?w=400&h=300&fit=crop"
    },
    {
        id: 17,
        name: "Orange Juice",
        category: "Beverages",
        price: 3.99,
        image: "https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?w=400&h=300&fit=crop"
    },
    {
        id: 18,
        name: "Coffee",
        category: "Beverages",
        price: 2.49,
        image: "https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&h=300&fit=crop"
    }
];

// Cart functionality
let cart = JSON.parse(localStorage.getItem('cart')) || {};

function updateCartCount() {
    const totalItems = Object.values(cart).reduce((sum, quantity) => sum + quantity, 0);
    document.getElementById('cartCount').textContent = totalItems;
}

function addToCart(itemId, quantity) {
    if (cart[itemId]) {
        cart[itemId] += quantity;
    } else {
        cart[itemId] = quantity;
    }
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    alert('Item added to cart!');
}

function filterMenu() {
    const selectedCategory = document.getElementById('categoryFilter').value;
    const filteredItems = selectedCategory === 'all' 
        ? menuItems 
        : menuItems.filter(item => item.category === selectedCategory);
    
    displayMenuItems(filteredItems);
}

function displayMenuItems(items) {
    const menuGrid = document.getElementById('menuGrid');
    menuGrid.innerHTML = '';

    if (items.length === 0) {
        menuGrid.innerHTML = '<p>No menu items found.</p>';
        return;
    }

    items.forEach(item => {
        const menuItem = document.createElement('div');
        menuItem.className = 'menu-item';
        menuItem.innerHTML = `
            <img src="${item.image}" alt="${item.name}">
            <div class="menu-item-content">
                <h3>${item.name}</h3>
                <div class="price">$${item.price.toFixed(2)}</div>
                <div class="add-to-cart">
                    <input type="number" value="1" min="1" id="qty-${item.id}">
                    <button onclick="addToCart(${item.id}, parseInt(document.getElementById('qty-${item.id}').value))">
                        Add to Cart
                    </button>
                </div>
            </div>
        `;
        menuGrid.appendChild(menuItem);
    });
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    displayMenuItems(menuItems);
    updateCartCount();
}); 