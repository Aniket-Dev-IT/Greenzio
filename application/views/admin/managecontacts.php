<?php 
// Contact messages management view for admin
?>

<div class="content">
    <div class="container-fluid">
        
        <?php if ($this->session->flashdata('item')) { ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong><?php echo $this->session->flashdata('item')['message']; ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Contact Messages Management</h4>
                        <p class="category">Manage customer inquiries and contact form submissions</p>
                    </div>
                    <div class="content table-responsive table-full-width">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <th>Message</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($contacts)) { 
                                    foreach ($contacts as $contact) { 
                                ?>
                                    <tr>
                                        <td><strong>#<?php echo $contact['contact_id']; ?></strong></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($contact['cnt_fname'] . ' ' . $contact['cnt_lname']); ?></strong>
                                        </td>
                                        <td>
                                            <i class="fa fa-envelope"></i> 
                                            <a href="mailto:<?php echo $contact['cnt_email']; ?>">
                                                <?php echo htmlspecialchars($contact['cnt_email']); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <i class="fa fa-calendar"></i> 
                                            <?php 
                                            if ($contact['cnt_date'] != '0000-00-00' && !empty($contact['cnt_date'])) {
                                                echo date('M d, Y', strtotime($contact['cnt_date'])); 
                                            } else {
                                                echo '<span class="text-muted">Date not available</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div style="max-width: 300px;">
                                                <?php 
                                                $message = htmlspecialchars($contact['cnt_message']);
                                                if (strlen($message) > 100) {
                                                    echo '<span id="short_msg_' . $contact['contact_id'] . '">' . 
                                                         substr($message, 0, 100) . '... 
                                                         <a href="#" onclick="toggleMessage(' . $contact['contact_id'] . '); return false;" class="text-primary">Read More</a>
                                                         </span>';
                                                    echo '<span id="full_msg_' . $contact['contact_id'] . '" style="display:none;">' . 
                                                         $message . ' 
                                                         <a href="#" onclick="toggleMessage(' . $contact['contact_id'] . '); return false;" class="text-primary">Show Less</a>
                                                         </span>';
                                                } else {
                                                    echo $message;
                                                }
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="mailto:<?php echo $contact['cnt_email']; ?>?subject=Re: Your Inquiry&body=Hello <?php echo htmlspecialchars($contact['cnt_fname']); ?>,%0A%0AThank you for contacting us." 
                                                   class="btn btn-success btn-sm" 
                                                   title="Reply via Email">
                                                    <i class="fa fa-reply"></i> Reply
                                                </a>
                                                <a href="<?php echo base_url('admin/deleteContact?id=' . $contact['contact_id']); ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   title="Delete Message"
                                                   onclick="return confirm('Are you sure you want to delete this contact message? This action cannot be undone!')">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } 
                                } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <i class="fa fa-envelope fa-3x text-muted"></i><br>
                                            <strong>No contact messages found</strong><br>
                                            <small>Customer inquiries will appear here when they contact you.</small>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        
                        <?php if (!empty($contacts)) { ?>
                            <div class="footer">
                                <hr>
                                <div class="stats">
                                    <i class="fa fa-envelope"></i> Total Messages: <strong><?php echo count($contacts); ?></strong>
                                    <?php
                                    // Count unique customers
                                    $unique_emails = array_unique(array_column($contacts, 'cnt_email'));
                                    ?>
                                    | Unique Customers: <strong><?php echo count($unique_emails); ?></strong>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Management Instructions -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Contact Management Guide</h4>
                        <p class="category">How to handle customer inquiries effectively</p>
                    </div>
                    <div class="content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <strong><i class="fa fa-info-circle"></i> Quick Actions:</strong>
                                    <ul class="mb-0">
                                        <li><strong>Reply:</strong> Click Reply to respond via your default email client</li>
                                        <li><strong>Delete:</strong> Remove messages you've already handled</li>
                                        <li><strong>Email Links:</strong> Click customer email to copy or contact directly</li>
                                        <li><strong>Read More:</strong> Expand long messages to read full content</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-success">
                                    <strong><i class="fa fa-lightbulb"></i> Best Practices:</strong>
                                    <ul class="mb-0">
                                        <li><strong>Respond Quickly:</strong> Reply within 24 hours when possible</li>
                                        <li><strong>Be Professional:</strong> Maintain courteous and helpful tone</li>
                                        <li><strong>Track Conversations:</strong> Keep notes of complex inquiries</li>
                                        <li><strong>Follow Up:</strong> Check if customer needs additional help</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function toggleMessage(contactId) {
    var shortMsg = document.getElementById('short_msg_' + contactId);
    var fullMsg = document.getElementById('full_msg_' + contactId);
    
    if (shortMsg.style.display === 'none') {
        shortMsg.style.display = 'inline';
        fullMsg.style.display = 'none';
    } else {
        shortMsg.style.display = 'none';
        fullMsg.style.display = 'inline';
    }
}
</script>
