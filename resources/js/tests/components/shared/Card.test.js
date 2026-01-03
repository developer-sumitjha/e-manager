import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount } from '@vue/test-utils'

// Mock the Card component
const Card = {
  name: 'Card',
  template: `
    <div 
      :class="['card', cardClass]"
      :data-testid="testId"
    >
      <div v-if="$slots.header" class="card-header" data-testid="card-header">
        <slot name="header"></slot>
      </div>
      
      <div class="card-body" data-testid="card-body">
        <slot></slot>
      </div>
      
      <div v-if="$slots.footer" class="card-footer" data-testid="card-footer">
        <slot name="footer"></slot>
      </div>
    </div>
  `,
  props: {
    testId: {
      type: String,
      default: 'card'
    },
    variant: {
      type: String,
      default: 'default',
      validator: (value) => ['default', 'primary', 'success', 'warning', 'danger', 'info'].includes(value)
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value)
    },
    shadow: {
      type: String,
      default: 'md',
      validator: (value) => ['none', 'sm', 'md', 'lg'].includes(value)
    }
  },
  computed: {
    cardClass() {
      const classes = []
      
      if (this.variant !== 'default') {
        classes.push(`card-${this.variant}`)
      }
      
      if (this.size !== 'md') {
        classes.push(`card-${this.size}`)
      }
      
      if (this.shadow !== 'md') {
        classes.push(`shadow-${this.shadow}`)
      }
      
      return classes.join(' ')
    }
  }
}

describe('Card Component', () => {
  let wrapper

  it('renders basic card structure', () => {
    wrapper = mount(Card, {
      slots: {
        default: 'Card content'
      }
    })

    expect(wrapper.find('[data-testid="card"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="card-body"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="card-body"]').text()).toBe('Card content')
  })

  it('renders card with header', () => {
    wrapper = mount(Card, {
      slots: {
        header: 'Card Header',
        default: 'Card content'
      }
    })

    expect(wrapper.find('[data-testid="card-header"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="card-header"]').text()).toBe('Card Header')
  })

  it('renders card with footer', () => {
    wrapper = mount(Card, {
      slots: {
        default: 'Card content',
        footer: 'Card Footer'
      }
    })

    expect(wrapper.find('[data-testid="card-footer"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="card-footer"]').text()).toBe('Card Footer')
  })

  it('renders card with header, body, and footer', () => {
    wrapper = mount(Card, {
      slots: {
        header: 'Card Header',
        default: 'Card content',
        footer: 'Card Footer'
      }
    })

    expect(wrapper.find('[data-testid="card-header"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="card-body"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="card-footer"]').exists()).toBe(true)
  })

  it('applies variant classes correctly', () => {
    const variants = ['primary', 'success', 'warning', 'danger', 'info']
    
    variants.forEach(variant => {
      const testWrapper = mount(Card, {
        props: { variant },
        slots: { default: 'Content' }
      })
      
      expect(testWrapper.classes()).toContain(`card-${variant}`)
    })
  })

  it('applies size classes correctly', () => {
    const sizes = ['sm', 'md', 'lg', 'xl']
    
    sizes.forEach(size => {
      const testWrapper = mount(Card, {
        props: { size },
        slots: { default: 'Content' }
      })
      
      expect(testWrapper.classes()).toContain(`card-${size}`)
    })
  })

  it('applies shadow classes correctly', () => {
    const shadows = ['none', 'sm', 'md', 'lg']
    
    shadows.forEach(shadow => {
      const testWrapper = mount(Card, {
        props: { shadow },
        slots: { default: 'Content' }
      })
      
      expect(testWrapper.classes()).toContain(`shadow-${shadow}`)
    })
  })

  it('uses custom test id', () => {
    wrapper = mount(Card, {
      props: { testId: 'custom-card' },
      slots: { default: 'Content' }
    })

    expect(wrapper.find('[data-testid="custom-card"]').exists()).toBe(true)
  })

  it('handles complex slot content', () => {
    wrapper = mount(Card, {
      slots: {
        header: '<h3>Complex Header</h3><p>Subtitle</p>',
        default: '<div><p>Main content</p><ul><li>Item 1</li><li>Item 2</li></ul></div>',
        footer: '<button>Action</button>'
      }
    })

    expect(wrapper.find('[data-testid="card-header"]').html()).toContain('<h3>Complex Header</h3>')
    expect(wrapper.find('[data-testid="card-body"]').html()).toContain('<ul>')
    expect(wrapper.find('[data-testid="card-footer"]').html()).toContain('<button>Action</button>')
  })

  it('does not render header when no header slot provided', () => {
    wrapper = mount(Card, {
      slots: {
        default: 'Content'
      }
    })

    expect(wrapper.find('[data-testid="card-header"]').exists()).toBe(false)
  })

  it('does not render footer when no footer slot provided', () => {
    wrapper = mount(Card, {
      slots: {
        default: 'Content'
      }
    })

    expect(wrapper.find('[data-testid="card-footer"]').exists()).toBe(false)
  })

  it('applies multiple classes correctly', () => {
    wrapper = mount(Card, {
      props: {
        variant: 'primary',
        size: 'lg',
        shadow: 'lg'
      },
      slots: { default: 'Content' }
    })

    expect(wrapper.classes()).toContain('card-primary')
    expect(wrapper.classes()).toContain('card-lg')
    expect(wrapper.classes()).toContain('shadow-lg')
  })

  it('handles empty content gracefully', () => {
    wrapper = mount(Card, {
      slots: {
        default: ''
      }
    })

    expect(wrapper.find('[data-testid="card-body"]').exists()).toBe(true)
    expect(wrapper.find('[data-testid="card-body"]').text()).toBe('')
  })
})


