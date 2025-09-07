<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-envelope me-2" style="color: #42a5f5;"></i>
                            Messages & Contacts
                        </h4>
                        <p class="text-muted mb-0">Manage customer inquiries and contact messages</p>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="messageFilter" id="allMessages" checked>
                                    <label class="btn btn-outline-primary" for="allMessages">All Messages</label>
                                    
                                    <input type="radio" class="btn-check" name="messageFilter" id="unreadMessages">
                                    <label class="btn btn-outline-warning" for="unreadMessages">Unread</label>
                                    
                                    <input type="radio" class="btn-check" name="messageFilter" id="readMessages">
                                    <label class="btn btn-outline-success" for="readMessages">Read</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search messages...">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($messages)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Subject</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($messages as $message): ?>
                                            <tr class="<?php echo $message['status'] === 'unread' ? 'table-warning' : ''; ?>">
                                                <td><?php echo $message['id']; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if ($message['status'] === 'unread'): ?>
                                                            <i class="fas fa-circle text-warning me-2" style="font-size: 8px;"></i>
                                                        <?php endif; ?>
                                                        <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($message['email']); ?></td>
                                                <td>
                                                    <span class="fw-bold"><?php echo htmlspecialchars($message['subject']); ?></span>
                                                    <br>
                                                    <small class="text-muted"><?php echo substr(htmlspecialchars($message['message']), 0, 50); ?>...</small>
                                                </td>
                                                <td><?php echo date('M d, Y H:i', strtotime($message['date'])); ?></td>
                                                <td>
                                                    <?php if ($message['status'] === 'unread'): ?>
                                                        <span class="badge badge-warning">Unread</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-success">Read</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-info" onclick="viewMessage(<?php echo $message['id']; ?>)">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                        <button class="btn btn-sm btn-success" onclick="replyMessage(<?php echo $message['id']; ?>)">
                                                            <i class="fas fa-reply"></i> Reply
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" onclick="deleteMessage(<?php echo $message['id']; ?>)">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No messages found</h5>
                                <p class="text-muted">Customer messages will appear here when received.</p>
                                <button class="btn btn-primary" onclick="checkForNewMessages()">
                                    <i class="fas fa-sync"></i> Check for New Messages
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Messages Summary Cards -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope fa-2x me-3"></i>
                                    <div>
                                        <h5 class="mb-0"><?php echo count($messages ?? []); ?></h5>
                                        <small>Total Messages</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                                    <div>
                                        <h5 class="mb-0"><?php echo count(array_filter($messages ?? [], function($m) { return $m['status'] === 'unread'; })); ?></h5>
                                        <small>Unread Messages</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                    <div>
                                        <h5 class="mb-0"><?php echo count(array_filter($messages ?? [], function($m) { return $m['status'] === 'read'; })); ?></h5>
                                        <small>Read Messages</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock fa-2x me-3"></i>
                                    <div>
                                        <h5 class="mb-0"><?php echo !empty($messages) ? count(array_filter($messages, function($m) { return strtotime($m['date']) > strtotime('-24 hours'); })) : 0; ?></h5>
                                        <small>Last 24 Hours</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Message Modal -->
<div class="modal fade" id="viewMessageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-envelope me-2"></i>
                    Message Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="messageContent">
                    <!-- Message content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="replyToMessage()">
                    <i class="fas fa-reply"></i> Reply
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-reply me-2"></i>
                    Send Reply
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="replyForm">
                    <div class="mb-3">
                        <label for="replyTo" class="form-label">To</label>
                        <input type="email" class="form-control" id="replyTo" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="replySubject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="replySubject">
                    </div>
                    <div class="mb-3">
                        <label for="replyMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="replyMessage" rows="6" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="sendReply()">
                    <i class="fas fa-paper-plane"></i> Send Reply
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function viewMessage(messageId) {
    // Simulate loading message details
    const messageContent = `
        <div class="card">
            <div class="card-body">
                <h6><strong>From:</strong> John Doe &lt;john@example.com&gt;</h6>
                <h6><strong>Subject:</strong> Product Inquiry</h6>
                <h6><strong>Date:</strong> ${new Date().toLocaleDateString()}</h6>
                <hr>
                <p>Hello,</p>
                <p>I am interested in your organic vegetables. Could you please provide more information about your product range and delivery options?</p>
                <p>Thank you!</p>
                <p>Best regards,<br>John Doe</p>
            </div>
        </div>
    `;
    
    document.getElementById('messageContent').innerHTML = messageContent;
    new bootstrap.Modal(document.getElementById('viewMessageModal')).show();
}

function replyMessage(messageId) {
    // Pre-fill reply form
    document.getElementById('replyTo').value = 'john@example.com';
    document.getElementById('replySubject').value = 'Re: Product Inquiry';
    document.getElementById('replyMessage').value = 'Dear John,\n\nThank you for your inquiry about our organic vegetables.\n\n';
    
    new bootstrap.Modal(document.getElementById('replyModal')).show();
}

function replyToMessage() {
    // Close view modal and open reply modal
    bootstrap.Modal.getInstance(document.getElementById('viewMessageModal')).hide();
    setTimeout(() => {
        new bootstrap.Modal(document.getElementById('replyModal')).show();
    }, 300);
}

function sendReply() {
    const to = document.getElementById('replyTo').value;
    const subject = document.getElementById('replySubject').value;
    const message = document.getElementById('replyMessage').value;
    
    if (!message) {
        alert('Please enter a reply message.');
        return;
    }
    
    alert('Reply sent functionality would be implemented here.');
    
    // Close modal and reset form
    bootstrap.Modal.getInstance(document.getElementById('replyModal')).hide();
    document.getElementById('replyForm').reset();
}

function deleteMessage(messageId) {
    if (confirm('Are you sure you want to delete this message?')) {
        alert('Delete message functionality for ID: ' + messageId + ' would be implemented here.');
    }
}

function checkForNewMessages() {
    alert('Check for new messages functionality would be implemented here.');
}
</script>

<style>
.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    border-radius: 10px 10px 0 0 !important;
}

.badge {
    padding: 0.5rem 0.75rem;
    font-size: 0.75rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.btn-group .btn {
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.btn-check:checked + .btn {
    background-color: #27ae60;
    border-color: #27ae60;
    color: white;
}
</style>
