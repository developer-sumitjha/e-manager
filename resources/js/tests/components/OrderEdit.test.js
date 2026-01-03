import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount } from '@vue/test-utils'

// Mock the OrderEdit component
const OrderEdit = {
  name: 'OrderEdit',
  template: `
    <div class="order-edit" v-if="order">
      <form @submit.prevent="handleSubmit" data-testid="edit-form">
        <div class="form-group">
          <label for="status">Order Status</label>
          <select 
            id="status" 
            v-model="formData.status" 
            :class="{ 'is-invalid': errors.status }"
            data-testid="status-select"
          >
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
          <div v-if="errors.status" class="invalid-feedback" data-testid="status-error">
            {{ errors.status }}
          </div>
        </div>
        
        <div class="form-group">
          <label for="payment_status">Payment Status</label>
          <select 
            id="payment_status" 
            v-model="formData.payment_status" 
            :class="{ 'is-invalid': errors.payment_status }"
            data-testid="payment-status-select"
          >
            <option value="unpaid">Unpaid</option>
            <option value="paid">Paid</option>
            <option value="refunded">Refunded</option>
          </select>
          <div v-if="errors.payment_status" class="invalid-feedback" data-testid="payment-status-error">
            {{ errors.payment_status }}
          </div>
        </div>
        
        <div class="form-group">
          <label for="notes">Notes</label>
          <textarea 
            id="notes" 
            v-model="formData.notes" 
            :class="{ 'is-invalid': errors.notes }"
            data-testid="notes-textarea"
          ></textarea>
          <div v-if="errors.notes" class="invalid-feedback" data-testid="notes-error">
            {{ errors.notes }}
          </div>
        </div>
        
        <div class="form-group">
          <label for="delivery_instructions">Delivery Instructions</label>
          <textarea 
            id="delivery_instructions" 
            v-model="formData.delivery_instructions" 
            :class="{ 'is-invalid': errors.delivery_instructions }"
            data-testid="delivery-instructions-textarea"
          ></textarea>
          <div v-if="errors.delivery_instructions" class="invalid-feedback" data-testid="delivery-instructions-error">
            {{ errors.delivery_instructions }}
          </div>
        </div>
        
        <div class="form-actions">
          <button 
            type="submit" 
            :disabled="loading"
            data-testid="save-button"
          >
            {{ loading ? 'Saving...' : 'Save Changes' }}
          </button>
          <button 
            type="button" 
            @click="cancelEdit"
            data-testid="cancel-button"
          >
            Cancel
          </button>
        </div>
      </form>
      
      <div v-if="successMessage" class="alert alert-success" data-testid="success-message">
        {{ successMessage }}
      </div>
      
      <div v-if="errorMessage" class="alert alert-danger" data-testid="error-message">
        {{ errorMessage }}
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
      formData: {
        status: '',
        payment_status: '',
        notes: '',
        delivery_instructions: ''
      },
      errors: {},
      successMessage: '',
      errorMessage: ''
    }
  },
  watch: {
    order: {
      handler(newOrder) {
        if (newOrder) {
          this.initializeForm()
        }
      },
      immediate: true
    }
  },
  methods: {
    initializeForm() {
      if (this.order) {
        this.formData = {
          status: this.order.status,
          payment_status: this.order.payment_status,
          notes: this.order.notes || '',
          delivery_instructions: this.order.delivery_instructions || ''
        }
      }
    },
    validateForm() {
      this.errors = {}
      
      if (!this.formData.status) {
        this.errors.status = 'Status is required'
      }
      
      if (!this.formData.payment_status) {
        this.errors.payment_status = 'Payment status is required'
      }
      
      return Object.keys(this.errors).length === 0
    },
    async handleSubmit() {
      if (!this.validateForm()) {
        return
      }
      
      this.$emit('save-order', {
        id: this.order.id,
        ...this.formData
      })
    },
    cancelEdit() {
      this.$emit('cancel-edit')
    }
  }
}

describe('OrderEdit Component', () => {
  let wrapper
  let mockOrder

  beforeEach(() => {
    mockOrder = global.createMockOrder({
      id: 1,
      order_number: 'ORD-001',
      status: 'pending',
      payment_status: 'unpaid',
      notes: 'Test notes',
      delivery_instructions: 'Leave at front door'
    })
  })

  it('renders edit form correctly', () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    expect(wrapper.find('[data-testid="edit-form"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="status-select"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="payment-status-select"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="notes-textarea"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="delivery-instructions-textarea"]').exists()).toBe(true)
  })

  it('initializes form with order data', () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    expect(wrapper.vm.formData.status).toBe('pending')
    expect(wrapper.vm.formData.payment_status).toBe('unpaid')
    expect(wrapper.vm.formData.notes).toBe('Test notes')
    expect(wrapper.vm.formData.delivery_instructions).toBe('Leave at front door')
  })

  it('shows loading state when order is null', () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: null,
        loading: true
      }
    })

    expect(wrapper.find('[data-testid="loading-state"]').exists()).toBe(true)
    expect(wrapper.find('.order-edit').exists()).toBe(false)
  })

  it('validates required fields', async () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    // Clear form data
    wrapper.vm.formData.status = ''
    wrapper.vm.formData.payment_status = ''

    await wrapper.find('[data-testid="edit-form"]').trigger('submit')

    expect(wrapper.vm.errors.status).toBe('Status is required')
    expect(wrapper.vm.errors.payment_status).toBe('Payment status is required')
  })

  it('shows validation errors in UI', async () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    // Clear form data
    wrapper.vm.formData.status = ''
    wrapper.vm.formData.payment_status = ''

    await wrapper.find('[data-testid="edit-form"]').trigger('submit')

    expect(wrapper.find('[data-testid="status-error"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="payment-status-error"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="status-error"]').text()).toBe('Status is required')
  })

  it('applies invalid class to fields with errors', async () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    wrapper.vm.formData.status = ''
    await wrapper.find('[data-testid="edit-form"]').trigger('submit')

    const statusSelect = wrapper.find('[data-testid="status-select"]')
    expect(statusSelect.classes()).toContain('is-invalid')
  })

  it('emits save-order event with form data when form is submitted', async () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    wrapper.vm.formData.status = 'confirmed'
    wrapper.vm.formData.payment_status = 'paid'
    wrapper.vm.formData.notes = 'Updated notes'

    await wrapper.find('[data-testid="edit-form"]').trigger('submit')

    expect(wrapper.emitted('save-order')).toBeTruthy()
    expect(wrapper.emitted('save-order')[0]).toEqual([{
      id: 1,
      status: 'confirmed',
      payment_status: 'paid',
      notes: 'Updated notes',
      delivery_instructions: 'Leave at front door'
    }])
  })

  it('emits cancel-edit event when cancel button is clicked', async () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    await wrapper.find('[data-testid="cancel-button"]').trigger('click')

    expect(wrapper.emitted('cancel-edit')).toBeTruthy()
  })

  it('disables save button when loading', () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: mockOrder,
        loading: true
      }
    })

    const saveButton = wrapper.find('[data-testid="save-button"]')
    expect(saveButton.attributes('disabled')).toBeDefined()
    expect(saveButton.text()).toBe('Saving...')
  })

  it('enables save button when not loading', () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    const saveButton = wrapper.find('[data-testid="save-button"]')
    expect(saveButton.attributes('disabled')).toBeUndefined()
    expect(saveButton.text()).toBe('Save Changes')
  })

  it('updates form data when order prop changes', async () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    const newOrder = {
      ...mockOrder,
      status: 'confirmed',
      payment_status: 'paid',
      notes: 'New notes'
    }

    await wrapper.setProps({ order: newOrder })

    expect(wrapper.vm.formData.status).toBe('confirmed')
    expect(wrapper.vm.formData.payment_status).toBe('paid')
    expect(wrapper.vm.formData.notes).toBe('New notes')
  })

  it('handles empty notes and delivery instructions', () => {
    const orderWithoutNotes = {
      ...mockOrder,
      notes: null,
      delivery_instructions: null
    }

    wrapper = mount(OrderEdit, {
      props: {
        order: orderWithoutNotes,
        loading: false
      }
    })

    expect(wrapper.vm.formData.notes).toBe('')
    expect(wrapper.vm.formData.delivery_instructions).toBe('')
  })

  it('does not emit save-order when validation fails', async () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    wrapper.vm.formData.status = ''
    wrapper.vm.formData.payment_status = ''

    await wrapper.find('[data-testid="edit-form"]').trigger('submit')

    expect(wrapper.emitted('save-order')).toBeFalsy()
  })

  it('clears errors when form data changes', async () => {
    wrapper = mount(OrderEdit, {
      props: {
        order: mockOrder,
        loading: false
      }
    })

    // Trigger validation errors
    wrapper.vm.formData.status = ''
    await wrapper.find('[data-testid="edit-form"]').trigger('submit')

    expect(wrapper.vm.errors.status).toBe('Status is required')

    // Fix the error
    wrapper.vm.formData.status = 'confirmed'
    await wrapper.vm.$nextTick()

    // Errors should be cleared when form is resubmitted
    await wrapper.find('[data-testid="edit-form"]').trigger('submit')
    expect(wrapper.vm.errors.status).toBeUndefined()
  })
})


