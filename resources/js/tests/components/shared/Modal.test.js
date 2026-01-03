import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount } from '@vue/test-utils'

// Mock the Modal component
const Modal = {
  name: 'Modal',
  template: `
    <div 
      v-if="show" 
      class="modal fade show" 
      :class="{ 'd-block': show }"
      :data-testid="testId"
      @click="handleBackdropClick"
    >
      <div class="modal-dialog" :class="modalClass" @click.stop>
        <div class="modal-content">
          <div v-if="showHeader" class="modal-header" data-testid="modal-header">
            <h5 class="modal-title" data-testid="modal-title">{{ title }}</h5>
            <button 
              v-if="showCloseButton" 
              type="button" 
              class="btn-close" 
              @click="close"
              data-testid="close-button"
            ></button>
          </div>
          
          <div class="modal-body" data-testid="modal-body">
            <slot></slot>
          </div>
          
          <div v-if="showFooter" class="modal-footer" data-testid="modal-footer">
            <slot name="footer">
              <button 
                type="button" 
                class="btn btn-secondary" 
                @click="close"
                data-testid="cancel-button"
              >
                {{ cancelText }}
              </button>
              <button 
                type="button" 
                class="btn btn-primary" 
                @click="confirm"
                :disabled="loading"
                data-testid="confirm-button"
              >
                {{ loading ? loadingText : confirmText }}
              </button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  `,
  props: {
    show: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'Modal Title'
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value)
    },
    showHeader: {
      type: Boolean,
      default: true
    },
    showFooter: {
      type: Boolean,
      default: true
    },
    showCloseButton: {
      type: Boolean,
      default: true
    },
    confirmText: {
      type: String,
      default: 'Confirm'
    },
    cancelText: {
      type: String,
      default: 'Cancel'
    },
    loadingText: {
      type: String,
      default: 'Loading...'
    },
    loading: {
      type: Boolean,
      default: false
    },
    closeOnBackdrop: {
      type: Boolean,
      default: true
    },
    testId: {
      type: String,
      default: 'modal'
    }
  },
  computed: {
    modalClass() {
      const classes = []
      
      if (this.size !== 'md') {
        classes.push(`modal-${this.size}`)
      }
      
      return classes.join(' ')
    }
  },
  methods: {
    close() {
      this.$emit('close')
    },
    confirm() {
      this.$emit('confirm')
    },
    handleBackdropClick(event) {
      if (this.closeOnBackdrop && event.target === event.currentTarget) {
        this.close()
      }
    }
  }
}

describe('Modal Component', () => {
  let wrapper

  it('renders modal when show is true', () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        title: 'Test Modal'
      },
      slots: {
        default: 'Modal content'
      }
    })

    expect(wrapper.find('[data-testid="modal"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="modal-title"]').text()).toBe('Test Modal')
    expect(wrapper.find('[data-testid="modal-body"]').text()).toBe('Modal content')
  })

  it('does not render modal when show is false', () => {
    wrapper = mount(Modal, {
      props: {
        show: false
      }
    })

    expect(wrapper.find('[data-testid="modal"]').exists()).toBe(false)
  })

  it('renders header when showHeader is true', () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        showHeader: true,
        title: 'Test Title'
      }
    })

    expect(wrapper.find('[data-testid="modal-header"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="modal-title"]').text()).toBe('Test Title')
  })

  it('does not render header when showHeader is false', () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        showHeader: false
      }
    })

    expect(wrapper.find('[data-testid="modal-header"]').exists()).toBe(false)
  })

  it('renders footer when showFooter is true', () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        showFooter: true
      }
    })

    expect(wrapper.find('[data-testid="modal-footer"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="confirm-button"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="cancel-button"]').exists()).toBe(true)
  })

  it('does not render footer when showFooter is false', () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        showFooter: false
      }
    })

    expect(wrapper.find('[data-testid="modal-footer"]').exists()).toBe(false)
  })

  it('renders close button when showCloseButton is true', () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        showCloseButton: true
      }
    })

    expect(wrapper.find('[data-testid="close-button"]').exists()).toBe(true)
  })

  it('does not render close button when showCloseButton is false', () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        showCloseButton: false
      }
    })

    expect(wrapper.find('[data-testid="close-button"]').exists()).toBe(false)
  })

  it('applies size classes correctly', () => {
    const sizes = ['sm', 'md', 'lg', 'xl']
    
    sizes.forEach(size => {
      const testWrapper = mount(Modal, {
        props: {
          show: true,
          size
        }
      })
      
      const modalDialog = testWrapper.find('.modal-dialog')
      if (size !== 'md') {
        expect(modalDialog.classes()).toContain(`modal-${size}`)
      }
    })
  })

  it('emits close event when close button is clicked', async () => {
    wrapper = mount(Modal, {
      props: {
        show: true
      }
    })

    await wrapper.find('[data-testid="close-button"]').trigger('click')
    expect(wrapper.emitted('close')).toBeTruthy()
  })

  it('emits close event when cancel button is clicked', async () => {
    wrapper = mount(Modal, {
      props: {
        show: true
      }
    })

    await wrapper.find('[data-testid="cancel-button"]').trigger('click')
    expect(wrapper.emitted('close')).toBeTruthy()
  })

  it('emits confirm event when confirm button is clicked', async () => {
    wrapper = mount(Modal, {
      props: {
        show: true
      }
    })

    await wrapper.find('[data-testid="confirm-button"]').trigger('click')
    expect(wrapper.emitted('confirm')).toBeTruthy()
  })

  it('emits close event when backdrop is clicked and closeOnBackdrop is true', async () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        closeOnBackdrop: true
      }
    })

    await wrapper.find('[data-testid="modal"]').trigger('click')
    expect(wrapper.emitted('close')).toBeTruthy()
  })

  it('does not emit close event when backdrop is clicked and closeOnBackdrop is false', async () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        closeOnBackdrop: false
      }
    })

    await wrapper.find('[data-testid="modal"]').trigger('click')
    expect(wrapper.emitted('close')).toBeFalsy()
  })

  it('does not emit close event when modal content is clicked', async () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        closeOnBackdrop: true
      }
    })

    await wrapper.find('.modal-dialog').trigger('click')
    expect(wrapper.emitted('close')).toBeFalsy()
  })

  it('shows loading state on confirm button', () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        loading: true
      }
    })

    const confirmButton = wrapper.find('[data-testid="confirm-button"]')
    expect(confirmButton.attributes('disabled')).toBeDefined()
    expect(confirmButton.text()).toBe('Loading...')
  })

  it('uses custom button texts', () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        confirmText: 'Save',
        cancelText: 'Close',
        loadingText: 'Saving...'
      }
    })

    expect(wrapper.find('[data-testid="confirm-button"]').text()).toBe('Save')
    expect(wrapper.find('[data-testid="cancel-button"]').text()).toBe('Close')
  })

  it('renders custom footer slot', () => {
    wrapper = mount(Modal, {
      props: {
        show: true
      },
      slots: {
        footer: '<button class="btn btn-danger">Delete</button>'
      }
    })

    expect(wrapper.find('[data-testid="modal-footer"]').html()).toContain('<button class="btn btn-danger">Delete</button>')
    expect(wrapper.find('[data-testid="confirm-button"]').exists()).toBe(false)
    expect(wrapper.find('[data-testid="cancel-button"]').exists()).toBe(false)
  })

  it('handles complex content in body', () => {
    wrapper = mount(Modal, {
      props: {
        show: true
      },
      slots: {
        default: `
          <div>
            <h4>Complex Content</h4>
            <form>
              <div class="form-group">
                <label>Name:</label>
                <input type="text" class="form-control">
              </div>
              <div class="form-group">
                <label>Email:</label>
                <input type="email" class="form-control">
              </div>
            </form>
          </div>
        `
      }
    })

    expect(wrapper.find('[data-testid="modal-body"]').html()).toContain('<h4>Complex Content</h4>')
    expect(wrapper.find('[data-testid="modal-body"]').html()).toContain('<form>')
  })

  it('uses custom test id', () => {
    wrapper = mount(Modal, {
      props: {
        show: true,
        testId: 'custom-modal'
      }
    })

    expect(wrapper.find('[data-testid="custom-modal"]').exists()).toBe(true)
  })
})


