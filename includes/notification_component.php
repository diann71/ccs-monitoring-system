<?php
require_once 'notification_functions.php';

function displayNotificationBell($user_id = null, $admin_id = null) {
    $count = getNotificationCount($user_id, $admin_id);
    ?>
    <div class="notification-bell">
        <a href="#" class="notification-link" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i>
            <?php if ($count > 0): ?>
                <span class="notification-badge"><?php echo $count; ?></span>
            <?php endif; ?>
        </a>
        <div class="dropdown-menu notification-dropdown">
            <div class="notification-header">
                <h6>Notifications</h6>
            </div>
            <div class="notification-body">
                <?php
                $notifications = getUnreadNotifications($user_id, $admin_id);
                if (empty($notifications)) {
                    echo '<p class="text-center">No new notifications</p>';
                } else {
                    foreach ($notifications as $notification) {
                        // Determine the link based on notification type
                        $link = '#';
                        switch ($notification['type']) {
                            case 'reservation':
                                $link = 'admin_reservation.php';
                                break;
                            case 'reservation_approved':
                                $link = 'admin_reservation.php';
                                break;
                            case 'announcement':
                                $link = 'dashboard.php';
                                break;
                            case 'feedback':
                                $link = 'admin_feedback.php';
                                break;
                            default:
                                $link = 'dashboard.php';
                        }
                        ?>
                        <a href="<?php echo $link; ?>" class="notification-item" data-id="<?php echo $notification['id']; ?>">
                            <div class="notification-content">
                                <p><?php echo htmlspecialchars($notification['message']); ?></p>
                                <small class="text-muted"><?php echo date('M d, Y h:i A', strtotime($notification['created_at'])); ?></small>
                            </div>
                        </a>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <style>
        .notification-bell {
            position: relative;
            display: inline-block;
        }
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #ff3b3b, #ff6b6b);
            color: white;
            border-radius: 50%;
            padding: 1px 4px;
            font-size: 10px;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(255, 59, 59, 0.4);
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid #fff;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 59, 59, 0.4);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(255, 59, 59, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 59, 59, 0);
            }
        }
        .notification-badge:hover {
            transform: scale(1.15);
            box-shadow: 0 4px 12px rgba(255, 59, 59, 0.5);
        }
        .notification-dropdown {
            width: 320px;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            position: absolute;
            right: 0;
            z-index: 100;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.08);
            animation: slideDown 0.2s ease-out;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .notification-bell.active .notification-dropdown {
            display: block;
        }
        .notification-header {
            padding: 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            background: #f8f9fa;
            border-radius: 12px 12px 0 0;
        }
        .notification-header h6 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
        }
        .notification-body {
            padding: 8px;
        }
        .notification-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #fff;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
            position: relative;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
            border-color: rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }
        .notification-content p {
            margin-bottom: 4px;
            color: #2c3e50;
            font-size: 0.95rem;
            line-height: 1.4;
        }
        .notification-content small {
            color: #6c757d;
            font-size: 0.8rem;
        }
        .notification-actions {
            display: flex;
            gap: 12px;
            margin-top: 4px;
        }
        .notification-actions .icon {
            cursor: pointer;
            font-size: 1.2em;
            vertical-align: middle;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle notification dropdown
        const bell = document.querySelector('.notification-bell');
        const bellLink = bell.querySelector('.notification-link');
        bellLink.addEventListener('click', function(e) {
            e.preventDefault();
            bell.classList.toggle('active');
        });
        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!bell.contains(e.target)) {
                bell.classList.remove('active');
            }
        });

        // Mark notification as read
        const notificationItems = document.querySelectorAll('.notification-item');
        notificationItems.forEach(item => {
            item.addEventListener('click', function() {
                const notificationId = this.dataset.id;
                markNotificationAsRead(notificationId);
                this.style.opacity = '0.5';
            });
        });
    });

    function markNotificationAsRead(notificationId) {
        fetch('mark_notification_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'notification_id=' + notificationId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update notification count
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    const currentCount = parseInt(badge.textContent);
                    if (currentCount > 1) {
                        badge.textContent = currentCount - 1;
                    } else {
                        badge.remove();
                    }
                }
            }
        });
    }
    </script>
    <?php
}
?> 