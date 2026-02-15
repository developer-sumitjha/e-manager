<div id="navigation-tab" class="tab-pane">
    <form id="navigationForm">
        <?php echo csrf_field(); ?>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-bars"></i>
                Navigation Menu Management
            </h3>
            
            <div class="form-group">
                <label class="form-label">Manage Header Navigation Menu</label>
                <p class="text-muted small mb-3">Add, edit, delete, and reorder menu items for your storefront header.</p>
                
                <!-- Menu Items List -->
                <div class="menu-items-container" id="menuItemsContainer">
                    <?php
                        $navigationLinks = $settings->navigation_links ?? [];
                        // Default menu items if none exist
                        if (empty($navigationLinks)) {
                            $navigationLinks = [
                                ['label' => 'Home', 'url' => '/', 'type' => 'link', 'order' => 1],
                                ['label' => 'Products', 'url' => '#products', 'type' => 'link', 'order' => 2],
                                ['label' => 'About', 'url' => '#about', 'type' => 'link', 'order' => 3],
                                ['label' => 'Contact', 'url' => '#contact', 'type' => 'link', 'order' => 4],
                            ];
                        }
                        // Sort by order
                        usort($navigationLinks, function($a, $b) {
                            return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
                        });
                    ?>
                    
                    <?php $__currentLoopData = $navigationLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="menu-item-card" data-index="<?php echo e($index); ?>" data-order="<?php echo e($item['order'] ?? ($index + 1)); ?>">
                        <div class="menu-item-handle">
                            <i class="fas fa-grip-vertical"></i>
                        </div>
                        <div class="menu-item-content">
                            <div class="menu-item-header">
                                <strong class="menu-item-label"><?php echo e($item['label'] ?? 'Menu Item'); ?></strong>
                                <span class="menu-item-url text-muted small"><?php echo e($item['url'] ?? '#'); ?></span>
                            </div>
                            <div class="menu-item-actions">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editMenuItem(<?php echo e($index); ?>)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteMenuItem(<?php echo e($index); ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="menu_items[<?php echo e($index); ?>][label]" value="<?php echo e($item['label'] ?? ''); ?>">
                        <input type="hidden" name="menu_items[<?php echo e($index); ?>][url]" value="<?php echo e($item['url'] ?? ''); ?>">
                        <input type="hidden" name="menu_items[<?php echo e($index); ?>][type]" value="<?php echo e($item['type'] ?? 'link'); ?>">
                        <input type="hidden" name="menu_items[<?php echo e($index); ?>][order]" value="<?php echo e($item['order'] ?? ($index + 1)); ?>">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <?php if(empty($navigationLinks)): ?>
                    <div class="empty-menu-state">
                        <i class="fas fa-list-ul fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No menu items yet. Click "Add Menu Item" to get started.</p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Add Menu Item Button -->
                <div class="mt-3">
                    <button type="button" class="btn-builder btn-builder-outline" onclick="addMenuItem()">
                        <i class="fas fa-plus"></i>
                        Add Menu Item
                    </button>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-cog"></i>
                Navigation Settings
            </h3>
            
            <div class="form-group">
                <label class="form-label">Show Categories Menu</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_categories_menu" <?php echo e($settings->show_categories_menu ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show Search Bar</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_search_bar" <?php echo e($settings->show_search_bar ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show Cart Icon</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_cart_icon" <?php echo e($settings->show_cart_icon ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn-builder btn-builder-primary" onclick="saveNavigation()">
                <i class="fas fa-save"></i>
                Save Navigation
            </button>
        </div>
    </form>
</div>

<!-- Menu Item Modal -->
<div class="modal fade" id="menuItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuItemModalTitle">
                    <i class="fas fa-plus"></i> Add Menu Item
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="menuItemForm">
                    <div class="form-group mb-3">
                        <label class="form-label">Menu Label <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="menuItemLabel" name="label" placeholder="e.g., Home, Products, About" required>
                        <small class="text-muted">The text that appears in the navigation menu</small>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">URL / Link <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="menuItemUrl" name="url" placeholder="e.g., /, /products, #about, /contact" required>
                        <small class="text-muted">Use / for home, # for anchor links, or full URLs</small>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Link Type</label>
                        <select class="form-select" id="menuItemType" name="type">
                            <option value="link">Regular Link</option>
                            <option value="anchor">Anchor Link (#)</option>
                            <option value="external">External URL</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveMenuItem()">
                    <i class="fas fa-save"></i> Save Menu Item
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.menu-items-container {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 1rem;
    min-height: 100px;
    background: #f9f9f9;
}

.menu-item-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 0.75rem;
    cursor: move;
    transition: all 0.2s ease;
}

.menu-item-card:hover {
    border-color: #000000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.menu-item-card.dragging {
    opacity: 0.5;
    border-color: #000000;
}

.menu-item-handle {
    color: #666666;
    cursor: grab;
    font-size: 1.25rem;
    padding: 0.5rem;
}

.menu-item-handle:active {
    cursor: grabbing;
}

.menu-item-content {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.menu-item-header {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.menu-item-label {
    color: #000000;
    font-size: 0.9375rem;
}

.menu-item-url {
    font-size: 0.8125rem;
}

.menu-item-actions {
    display: flex;
    gap: 0.5rem;
}

.empty-menu-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #999999;
}

.empty-menu-state i {
    display: block;
    margin-bottom: 1rem;
}

.modal-content {
    border: 1px solid #000000;
    border-radius: 12px;
}

.modal-header {
    background: #ffffff;
    border-bottom: 1px solid #000000;
    padding: 1.25rem 1.5rem;
}

.modal-title {
    color: #000000;
    font-weight: 600;
    font-size: 1.125rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    background: #ffffff;
    border-top: 1px solid #e0e0e0;
    padding: 1rem 1.5rem;
}

.btn-close {
    opacity: 0.5;
}

.btn-close:hover {
    opacity: 1;
}
</style>

<script>
let currentMenuItemIndex = null;
let menuItems = <?php echo json_encode($navigationLinks ?? [], 15, 512) ?>;

// Initialize Sortable for drag and drop
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('menuItemsContainer');
    if (container && typeof Sortable !== 'undefined') {
        new Sortable(container, {
            handle: '.menu-item-handle',
            animation: 150,
            onEnd: function(evt) {
                updateMenuItemsOrder();
            }
        });
    } else if (container) {
        // Fallback: Load Sortable from CDN if not available
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js';
        script.onload = function() {
            new Sortable(container, {
                handle: '.menu-item-handle',
                animation: 150,
                onEnd: function(evt) {
                    updateMenuItemsOrder();
                }
            });
        };
        document.head.appendChild(script);
    }
});

function addMenuItem() {
    currentMenuItemIndex = null;
    document.getElementById('menuItemModalTitle').innerHTML = '<i class="fas fa-plus"></i> Add Menu Item';
    document.getElementById('menuItemForm').reset();
    document.getElementById('menuItemLabel').value = '';
    document.getElementById('menuItemUrl').value = '';
    document.getElementById('menuItemType').value = 'link';
    
    const modal = new bootstrap.Modal(document.getElementById('menuItemModal'));
    modal.show();
}

function editMenuItem(index) {
    currentMenuItemIndex = index;
    const item = menuItems[index];
    
    document.getElementById('menuItemModalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Menu Item';
    document.getElementById('menuItemLabel').value = item.label || '';
    document.getElementById('menuItemUrl').value = item.url || '';
    document.getElementById('menuItemType').value = item.type || 'link';
    
    const modal = new bootstrap.Modal(document.getElementById('menuItemModal'));
    modal.show();
}

function saveMenuItem() {
    const label = document.getElementById('menuItemLabel').value.trim();
    const url = document.getElementById('menuItemUrl').value.trim();
    const type = document.getElementById('menuItemType').value;
    
    if (!label || !url) {
        showAlert('Please fill in all required fields', 'danger');
        return;
    }
    
    const newItem = {
        label: label,
        url: url,
        type: type,
        order: currentMenuItemIndex !== null ? menuItems[currentMenuItemIndex].order : (menuItems.length + 1)
    };
    
    if (currentMenuItemIndex !== null) {
        // Update existing item
        menuItems[currentMenuItemIndex] = newItem;
    } else {
        // Add new item
        menuItems.push(newItem);
    }
    
    renderMenuItems();
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('menuItemModal'));
    modal.hide();
    
    showAlert('Menu item saved successfully!', 'success');
}

function deleteMenuItem(index) {
    if (!confirm('Are you sure you want to delete this menu item?')) {
        return;
    }
    
    menuItems.splice(index, 1);
    renderMenuItems();
    showAlert('Menu item deleted successfully!', 'success');
}

function renderMenuItems() {
    const container = document.getElementById('menuItemsContainer');
    
    if (menuItems.length === 0) {
        container.innerHTML = `
            <div class="empty-menu-state">
                <i class="fas fa-list-ul fa-3x text-muted mb-3"></i>
                <p class="text-muted">No menu items yet. Click "Add Menu Item" to get started.</p>
            </div>
        `;
        return;
    }
    
    // Sort by order
    menuItems.sort((a, b) => (a.order || 999) - (b.order || 999));
    
    // Update order values to be sequential
    menuItems.forEach((item, index) => {
        item.order = index + 1;
    });
    
    container.innerHTML = menuItems.map((item, index) => `
        <div class="menu-item-card" data-index="${index}" data-order="${item.order}">
            <div class="menu-item-handle">
                <i class="fas fa-grip-vertical"></i>
            </div>
            <div class="menu-item-content">
                <div class="menu-item-header">
                    <strong class="menu-item-label">${escapeHtml(item.label)}</strong>
                    <span class="menu-item-url text-muted small">${escapeHtml(item.url)}</span>
                </div>
                <div class="menu-item-actions">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editMenuItem(${index})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteMenuItem(${index})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
            <input type="hidden" name="menu_items[${index}][label]" value="${escapeHtml(item.label)}">
            <input type="hidden" name="menu_items[${index}][url]" value="${escapeHtml(item.url)}">
            <input type="hidden" name="menu_items[${index}][type]" value="${escapeHtml(item.type || 'link')}">
            <input type="hidden" name="menu_items[${index}][order]" value="${item.order}">
        </div>
    `).join('');
    
    // Reinitialize Sortable
    if (typeof Sortable !== 'undefined') {
        new Sortable(container, {
            handle: '.menu-item-handle',
            animation: 150,
            onEnd: function(evt) {
                updateMenuItemsOrder();
            }
        });
    }
}

function updateMenuItemsOrder() {
    const container = document.getElementById('menuItemsContainer');
    const cards = container.querySelectorAll('.menu-item-card');
    
    cards.forEach((card, index) => {
        const dataIndex = parseInt(card.getAttribute('data-index'));
        if (menuItems[dataIndex]) {
            menuItems[dataIndex].order = index + 1;
        }
    });
    
    // Re-render to update hidden inputs
    renderMenuItems();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function saveNavigation() {
    const form = document.getElementById('navigationForm');
    const formData = new FormData();
    
    formData.append('_token', '<?php echo e(csrf_token()); ?>');
    formData.append('show_categories_menu', form.querySelector('input[name="show_categories_menu"]').checked ? '1' : '0');
    formData.append('show_search_bar', form.querySelector('input[name="show_search_bar"]').checked ? '1' : '0');
    formData.append('show_cart_icon', form.querySelector('input[name="show_cart_icon"]').checked ? '1' : '0');
    
    // Prepare navigation links array
    const navigationLinks = menuItems.map((item, index) => ({
        label: item.label,
        url: item.url,
        type: item.type || 'link',
        order: item.order || (index + 1)
    }));
    
    formData.append('navigation_links', JSON.stringify(navigationLinks));
    
    showSaveIndicator('saving');
    
    fetch('<?php echo e(route('admin.site-builder.update-navigation')); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSaveIndicator('saved');
            showAlert('Navigation settings saved successfully!');
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .catch(error => {
        showSaveIndicator('saved');
        showAlert('Error: ' + error.message, 'danger');
    });
}
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/site-builder/tabs/navigation.blade.php ENDPATH**/ ?>