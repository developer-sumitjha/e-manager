<div class="notification-item d-flex align-items-start {{ $notification['read'] ? '' : 'unread' }} {{ $notification['priority'] === 'urgent' ? 'urgent' : '' }}" 
     data-notification-id="{{ $notification['id'] }}">
    
    <!-- Notification Icon -->
    <div class="notification-icon {{ $notification['type'] }}">
        <i class="{{ $notification['icon'] }}"></i>
    </div>
    
    <!-- Notification Content -->
    <div class="notification-content">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="notification-title">
                    {{ $notification['title'] }}
                    @if(!$notification['read'])
                        <span class="unread-indicator badge bg-primary ms-2">New</span>
                    @endif
                </h6>
                <p class="notification-message">{{ $notification['message'] }}</p>
                
                <div class="notification-meta">
                    <span class="priority-badge {{ $notification['priority'] }}">
                        {{ ucfirst($notification['priority']) }}
                    </span>
                    <span>
                        <i class="fas fa-clock"></i>
                        {{ $notification['timestamp']->diffForHumans() }}
                    </span>
                    <span>
                        <i class="fas fa-calendar"></i>
                        {{ $notification['timestamp']->format('M d, Y H:i') }}
                    </span>
                </div>
            </div>
            
            <!-- Actions Dropdown -->
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    @if(!$notification['read'])
                        <li>
                            <a class="dropdown-item" href="#" onclick="markAsRead('{{ $notification['id'] }}')">
                                <i class="fas fa-check"></i> Mark as Read
                            </a>
                        </li>
                    @endif
                    @if(isset($notification['actions']))
                        @foreach($notification['actions'] as $action)
                            <li>
                                <a class="dropdown-item" href="{{ $action['url'] }}">
                                    <i class="fas fa-external-link-alt"></i> {{ $action['label'] }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                    @if(str_starts_with($notification['id'], 'custom_'))
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="deleteNotification('{{ $notification['id'] }}')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        
        <!-- Quick Actions -->
        @if(isset($notification['actions']) && count($notification['actions']) > 0)
            <div class="notification-actions">
                @foreach($notification['actions'] as $action)
                    <a href="{{ $action['url'] }}" class="btn btn-sm btn-outline-primary">
                        {{ $action['label'] }}
                    </a>
                @endforeach
                @if(!$notification['read'])
                    <button class="btn btn-sm btn-outline-success" onclick="markAsRead('{{ $notification['id'] }}')">
                        <i class="fas fa-check"></i> Mark as Read
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>


