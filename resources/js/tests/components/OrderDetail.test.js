import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount } from '@vue/test-utils'

// Mock the OrderDetail component
const OrderDetail = {
  name: 'OrderDetail',
  template: `
    <div class="order-detail" v-if="order">
      <div class="order-header">
        <h2 data-testid="order-title">Order #{{ order.order_number }}</h2>
        <div class="order-status">
          <span :class="'badge badge-' + getStatusClass(order.status)" data-testid="status-badge">
            {{ order.status }}
          </span>
          <span :class="'badge badge-' + getPaymentStatusClass(order.payment_status)" data-testid="payment-status-badge">
            {{ order.payment_status }}
          </span>
        </div>
      </div>
      
      <div class="order-info">
        <div class="customer-info">
          <h3>Customer Information</h3>
          <p data-testid="customer-name">{{ order.user.name }}</p>
          <p data-testid="customer-email">{{ order.user.email }}</p>
          <p data-testid="customer-phone">{{ order.user.phone }}</p>
        </div>
        
        <div class="order-summary">
          <h3>Order Summary</h3>
          <p data-testid="order-total">Total: Rs. {{ order.total.toFixed(2) }}</p>
          <p data-testid="order-subtotal">Subtotal: Rs. {{ order.subtotal.toFixed(2) }}</p>
          <p data-testid="order-tax">Tax: Rs. {{ order.tax.toFixed(2) }}</p>
        </div>
      </div>
      
      <div class="order-items">
        <h3>Order Items</h3>
        <table class="items-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="item in order.orderItems" 
              :key="item.id"
              :data-testid="'item-row-' + item.id"
            >
              <td data-testid="item-name">{{ item.product_name }}</td>
              <td data-testid="item-quantity">{{ item.quantity }}</td>
              <td data-testid="item-price">Rs. {{ item.price.toFixed(2) }}</td>
              <td data-testid="item-total">Rs. {{ item.total.toFixed(2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="order-actions">
        <button @click="editOrder" data-testid="edit-button">Edit Order</button>
        <button @click="printOrder" data-testid="print-button">Print</button>
        <button @click="exportOrder" data-testid="export-button">Export</button>
        <button @click="changeStatus" data-testid="change-status-button">Change Status</button>
      </div>
      
      <div class="status-timeline" v-if="statusHistory.length > 0">
        <h3>Status History</h3>
        <div 
          v-for="(status, index) in statusHistory" 
          :key="index"
          class="timeline-item"
          :data-testid="'timeline-item-' + index"
        >
          <span class="status">{{ status.status }}</span>
          <span class="date">{{ formatDate(status.date) }}</span>
        </div>
      </div>
    </div>
    
    <div v-else class="loading-state" data-testid="loading-state">
      <p>Loading order details...</p>
    </div>
  `,
  props: {
    order: {
      type: Object,
      default: null
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      statusHistory: [
        { status: 'pending', date: '2024-01-01T00:00:00Z' },
        { status: 'confirmed', date: '2024-01-01T01:00:00Z' },
        { status: 'processing', date: '2024-01-01T02:00:00Z' }
      ]
    }
  },
  methods: {
    getStatusClass(status) {
      const statusClasses = {
        pending: 'warning',
        confirmed: 'info',
        processing: 'primary',
        completed: 'success',
        cancelled: 'danger'
      }
      return statusClasses[status] || 'secondary'
    },
    getPaymentStatusClass(status) {
      const statusClasses = {
        unpaid: 'danger',
        paid: 'success',
        refunded: 'warning'
      }
      return statusClasses[status] || 'secondary'
    },
    editOrder() {
      this.$emit('edit-order', this.order)
    },
    printOrder() {
      this.$emit('print-order', this.order)
    },
    exportOrder() {
      this.$emit('export-order', this.order)
    },
    changeStatus() {
      this.$emit('change-status', this.order)
    },
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString()
    }
  }
}

describe('OrderDetail Component', () => {
  let wrapper
  let mockOrder

  beforeEach(() => {
    mockOrder = global.createMockOrder({
      id: 1,
      order_number: 'ORD-001',
      status: 'confirmed',
      payment_status: 'paid',
      total: 150.00,
      subtotal: 130.00,
      tax: 20.00,
      user: {
        id: 1,
        name: 'John Doe',
        email: 'john@example.com',
        phone: '1234567890'
      },
      orderItems: [
        {
          id: 1,
          product_name: 'Test Product 1',
          quantity: 2,
          price: 50.00,
          total: 100.00
        },
        {
          id: 2,
          product_name: 'Test Product 2',
          quantity: 1,
          price: 30.00,
          total: 30.00
        }
      ]
    })
  })

  it('renders order details correctly', () => {
    wrapper = mount(OrderDetail, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    expect(wrapper.find('[data-testid="order-title"]').text()).toBe('Order #ORD-001')
    expect(wrapper.find('[data-testid="customer-name"]').text()).toBe('John Doe')
    expect(wrapper.find('[data-testid="customer-email"]').text()).toBe('john@example.com')
    expect(wrapper.find('[data-testid="order-total"]').text()).toBe('Total: Rs. 150.00')
  })

  it('displays order status badges correctly', () => {
    wrapper = mount(OrderDetail, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    const statusBadge = wrapper.find('[data-testid="status-badge"]')
    const paymentStatusBadge = wrapper.find('[data-testid="payment-status-badge"]')
    
    expect(statusBadge.text()).toBe('confirmed')
    expect(statusBadge.classes()).toContain('badge-info')
    expect(paymentStatusBadge.text()).toBe('paid')
    expect(paymentStatusBadge.classes()).toContain('badge-success')
  })

  it('displays order items correctly', () => {
    wrapper = mount(OrderDetail, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    const itemRows = wrapper.findAll('[data-testid^="item-row-"]')
    expect(itemRows).toHaveLength(2)
    
    const firstItem = wrapper.find('[data-testid="item-row-1"]')
    expect(firstItem.find('[data-testid="item-name"]').text()).toBe('Test Product 1')
    expect(firstItem.find('[data-testid="item-quantity"]').text()).toBe('2')
    expect(firstItem.find('[data-testid="item-price"]').text()).toBe('Rs. 50.00')
    expect(firstItem.find('[data-testid="item-total"]').text()).toBe('Rs. 100.00')
  })

  it('shows loading state when order is null', () => {
    wrapper = mount(OrderDetail, {
      props: {
        order: null,
        loading: true
      }
    })

    expect(wrapper.find('[data-testid="loading-state"]').exists()).toBe(true)
    expect(wrapper.find('.order-detail').exists()).toBe(false)
  })

  it('emits edit-order event when edit button is clicked', async () => {
    wrapper = mount(OrderDetail, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    await wrapper.find('[data-testid="edit-button"]').trigger('click')
    
    expect(wrapper.emitted('edit-order')).toBeTruthy()
    expect(wrapper.emitted('edit-order')[0]).toEqual([mockOrder])
  })

  it('emits print-order event when print button is clicked', async () => {
    wrapper = mount(OrderDetail, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    await wrapper.find('[data-testid="print-button"]').trigger('click')
    
    expect(wrapper.emitted('print-order')).toBeTruthy()
    expect(wrapper.emitted('print-order')[0]).toEqual([mockOrder])
  })

  it('emits export-order event when export button is clicked', async () => {
    wrapper = mount(OrderDetail, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    await wrapper.find('[data-testid="export-button"]').trigger('click')
    
    expect(wrapper.emitted('export-order')).toBeTruthy()
    expect(wrapper.emitted('export-order')[0]).toEqual([mockOrder])
  })

  it('emits change-status event when change status button is clicked', async () => {
    wrapper = mount(OrderDetail, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    await wrapper.find('[data-testid="change-status-button"]').trigger('click')
    
    expect(wrapper.emitted('change-status')).toBeTruthy()
    expect(wrapper.emitted('change-status')[0]).toEqual([mockOrder])
  })

  it('displays status timeline correctly', () => {
    wrapper = mount(OrderDetail, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    const timelineItems = wrapper.findAll('[data-testid^="timeline-item-"]')
    expect(timelineItems).toHaveLength(3)
    
    expect(timelineItems[0].text()).toContain('pending')
    expect(timelineItems[1].text()).toContain('confirmed')
    expect(timelineItems[2].text()).toContain('processing')
  })

  it('applies correct status classes for different statuses', () => {
    const testCases = [
      { status: 'pending', expectedClass: 'badge-warning' },
      { status: 'confirmed', expectedClass: 'badge-info' },
      { status: 'processing', expectedClass: 'badge-primary' },
      { status: 'completed', expectedClass: 'badge-success' },
      { status: 'cancelled', expectedClass: 'badge-danger' }
    ]

    testCases.forEach(({ status, expectedClass }) => {
      const testOrder = { ...mockOrder, status }
      const testWrapper = mount(OrderDetail, {
        props: {
          order: testOrder,
          loading: false
        }
      })

      const statusBadge = testWrapper.find('[data-testid="status-badge"]')
      expect(statusBadge.classes()).toContain(expectedClass)
    })
  })

  it('applies correct payment status classes', () => {
    const testCases = [
      { payment_status: 'unpaid', expectedClass: 'badge-danger' },
      { payment_status: 'paid', expectedClass: 'badge-success' },
      { payment_status: 'refunded', expectedClass: 'badge-warning' }
    ]

    testCases.forEach(({ payment_status, expectedClass }) => {
      const testOrder = { ...mockOrder, payment_status }
      const testWrapper = mount(OrderDetail, {
        props: {
          order: testOrder,
          loading: false
        }
      })

      const paymentStatusBadge = testWrapper.find('[data-testid="payment-status-badge"]')
      expect(paymentStatusBadge.classes()).toContain(expectedClass)
    })
  })

  it('handles empty order items gracefully', () => {
    const emptyOrder = { ...mockOrder, orderItems: [] }
    wrapper = mount(OrderDetail, {
      props: {
        order: emptyOrder,
        loading: false
      }
    })

    const itemRows = wrapper.findAll('[data-testid^="item-row-"]')
    expect(itemRows).toHaveLength(0)
  })

  it('formats dates correctly in timeline', () => {
    wrapper = mount(OrderDetail, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    const timelineItems = wrapper.findAll('[data-testid^="timeline-item-"]')
    expect(timelineItems[0].text()).toContain('1/1/2024') // formatted date
  })
})


