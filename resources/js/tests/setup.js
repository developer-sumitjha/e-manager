import { config } from '@vue/test-utils'
import { vi } from 'vitest'

// Mock global objects
global.vi = vi

// Mock window.location
Object.defineProperty(window, 'location', {
  value: {
    href: 'http://localhost:3000',
    origin: 'http://localhost:3000',
    pathname: '/',
    search: '',
    hash: '',
    assign: vi.fn(),
    replace: vi.fn(),
    reload: vi.fn(),
  },
  writable: true,
})

// Mock window.URL
global.URL = class URL {
  constructor(url, base) {
    this.href = url
    this.origin = base || 'http://localhost:3000'
    this.pathname = new URL(url, base).pathname
    this.search = new URL(url, base).search
  }
  
  toString() {
    return this.href
  }
}

// Mock fetch
global.fetch = vi.fn()

// Mock axios
vi.mock('axios', () => ({
  default: {
    get: vi.fn(),
    post: vi.fn(),
    put: vi.fn(),
    delete: vi.fn(),
    patch: vi.fn(),
    defaults: {
      headers: {
        common: {}
      }
    }
  }
}))

// Mock Bootstrap components
vi.mock('bootstrap', () => ({
  Modal: vi.fn().mockImplementation(() => ({
    show: vi.fn(),
    hide: vi.fn(),
    dispose: vi.fn(),
  })),
  Dropdown: vi.fn().mockImplementation(() => ({
    show: vi.fn(),
    hide: vi.fn(),
    dispose: vi.fn(),
  })),
  Toast: vi.fn().mockImplementation(() => ({
    show: vi.fn(),
    hide: vi.fn(),
    dispose: vi.fn(),
  })),
}))

// Mock Font Awesome
vi.mock('@fortawesome/fontawesome-svg-core', () => ({
  library: {
    add: vi.fn(),
  },
  dom: {
    watch: vi.fn(),
  },
}))

vi.mock('@fortawesome/free-solid-svg-icons', () => ({
  faUser: 'fa-user',
  faEdit: 'fa-edit',
  faTrash: 'fa-trash',
  faEye: 'fa-eye',
  faCheck: 'fa-check',
  faTimes: 'fa-times',
  faSearch: 'fa-search',
  faFilter: 'fa-filter',
  faDownload: 'fa-download',
  faPrint: 'fa-print',
}))

vi.mock('@fortawesome/vue-fontawesome', () => ({
  FontAwesomeIcon: {
    name: 'FontAwesomeIcon',
    template: '<i class="fa"></i>',
  },
}))

// Global test utilities
global.createMockOrder = (overrides = {}) => ({
  id: 1,
  order_number: 'ORD-001',
  user: {
    id: 1,
    name: 'John Doe',
    email: 'john@example.com',
    phone: '1234567890'
  },
  status: 'pending',
  payment_status: 'unpaid',
  total: 100.00,
  subtotal: 90.00,
  tax: 10.00,
  shipping: 0.00,
  created_at: '2024-01-01T00:00:00Z',
  updated_at: '2024-01-01T00:00:00Z',
  orderItems: [
    {
      id: 1,
      product_name: 'Test Product',
      quantity: 1,
      price: 100.00,
      total: 100.00
    }
  ],
  ...overrides
})

global.createMockOrders = (count = 5) => {
  return Array.from({ length: count }, (_, index) => 
    global.createMockOrder({
      id: index + 1,
      order_number: `ORD-${String(index + 1).padStart(3, '0')}`,
      user: {
        id: index + 1,
        name: `User ${index + 1}`,
        email: `user${index + 1}@example.com`,
        phone: `123456789${index}`
      }
    })
  )
}

// Mock console methods to reduce noise in tests
global.console = {
  ...console,
  log: vi.fn(),
  debug: vi.fn(),
  info: vi.fn(),
  warn: vi.fn(),
  error: vi.fn(),
}


