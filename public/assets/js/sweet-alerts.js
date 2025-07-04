// File: /public/js/sweet-alert-helper.js

window.SweetAlertHelper = {
    confirmDelete(count, entityName = null) {
        const message = entityName 
            ? `You are about to delete ${entityName}. This action cannot be undone.` 
            : `You are about to delete ${count} item(s). This action cannot be undone.`;
        return Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });
    },
    
    confirmApproval(count, entityName = null) {
        const message = entityName 
            ? `You are about to approve ${entityName}.`
            : `You are about to approve ${count} item(s).`;
        return Swal.fire({
            title: 'Confirm Approval',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, approve it!'
        });
    },
    
    confirmExport(count) {
        return Swal.fire({
            title: 'Export Data',
            text: `You are about to export ${count} item(s).`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Export'
        });
    },
    
    confirmDeactivation(count, entityName = null) {
        const message = entityName 
            ? `You are about to deactivate ${entityName}.`
            : `You are about to deactivate ${count} item(s).`;
        return Swal.fire({
            title: 'Confirm Deactivation',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, deactivate it!'
        });
    },
    
    confirmPasswordReset(entityName) {
        return Swal.fire({
            title: 'Reset Password',
            text: `You are about to reset the password for ${entityName}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, reset it!'
        });
    },
    
    // Loading state
    showLoading() {
        return Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we process your request.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    },
    
    // Success messages
    showSuccessMessage(action, count = 1, entityName = null) {
        let title, text;
        
        switch(action) {
            case 'delete':
                title = 'Deleted!';
                text = entityName 
                    ? `${entityName} has been deleted successfully.`
                    : `${count} item(s) have been deleted successfully.`;
                break;
            case 'approve':
                title = 'Approved!';
                text = entityName 
                    ? `${entityName} has been approved successfully.`
                    : `${count} item(s) have been approved successfully.`;
                break;
            case 'export':
                title = 'Exported!';
                text = `${count} item(s) have been exported successfully.`;
                break;
            case 'deactivate':
                title = 'Deactivated!';
                text = entityName 
                    ? `${entityName} has been deactivated successfully.`
                    : `${count} item(s) have been deactivated successfully.`;
                break;
            case 'reset_password':
                title = 'Password Reset!';
                text = `Password for ${entityName} has been reset successfully.`;
                break;
            case 'add':
                title = 'Added!';
                text = entityName 
                    ? `${entityName} has been added successfully.`
                    : `${count} item(s) have been added successfully.`;
                break;
            case 'upload':
                title = 'Uploaded!';
                text = entityName 
                    ? `${entityName} document has been uploaded successfully.`
                    : `${count} document(s) have been uploaded successfully.`;
                break;
            default:
                title = 'Success!';
                text = 'Operation completed successfully.';
        }
        
        return Swal.fire({
            title: title,
            text: text,
            icon: 'success',
            confirmButtonColor: '#10B981'
        });
    },
    
    // Error messages
    showErrorMessage(message) {
        return Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            confirmButtonColor: '#10B981'
        });
    },
    
    // Form validation with SweetAlert errors
    validateForm(formType, formData) {
        // For document upload
        if (formType === 'document_upload') {
            if (!formData.fileInput) {
                this.showErrorMessage('Please select a file to upload');
                return false;
            }
            
            if (!formData.document_type) {
                this.showErrorMessage('Please select a document type');
                return false;
            }
            
            if (formData.document_type === 'other' && !formData.custom_document_type) {
                this.showErrorMessage('Please specify the custom document type');
                return false;
            }
        }
        
        // For admin forms - password match validation
        if (formType === 'admin') {
            if (formData.password !== formData.password_confirmation) {
                this.showErrorMessage('Passwords do not match');
                return false;
            }
        }
        
        return true;
    },
    
    // Process form submission with modal integration
    processFormSubmission(formType, formData, modalId, options = {}) {
        // Show loading state
        this.showLoading();
        
        // Create a promise that represents the API call
        return new Promise((resolve, reject) => {
            // Simulate API request (replace with actual API call)
            setTimeout(() => {
                try {
                    // Determine entity name for success message
                    let entityName = '';
                    
                    if (formType === 'applicant' && formData.first_name) {
                        entityName = `${formData.first_name} ${formData.last_name}`;
                    } else if (formType === 'vehicle' && formData.license_plate) {
                        entityName = `Vehicle ${formData.license_plate}`;
                    } else if (formType === 'rfid' && formData.tag_number) {
                        entityName = `RFID ${formData.tag_number}`;
                    } else if (formType === 'admin' && formData.first_name) {
                        entityName = `Admin ${formData.first_name} ${formData.last_name}`;
                    } else if (formType === 'document_upload') {
                        entityName = formData.document_type === 'other' 
                            ? formData.custom_document_type 
                            : formData.document_type.replace('_', ' ');
                    }
                    
                    // Close the modal first
                    if (modalId && typeof window.closeModal === 'function') {
                        window.closeModal(modalId);
                    }
                    
                    // Then show success message
                    const action = options.action || (formType === 'document_upload' ? 'upload' : 'add');
                    this.showSuccessMessage(action, 1, entityName);
                    
                    // Resolve the promise with the result
                    resolve({
                        success: true,
                        entityName,
                        formData
                    });
                } catch (error) {
                    this.showErrorMessage('An error occurred while processing your request.');
                    reject(error);
                }
            }, options.delay || 1000);
        });
    },
    
    // Main function to handle confirmations
    confirmAction(action, count = 1, entityName = null) {
        switch(action) {
            case 'delete':
                return this.confirmDelete(count, entityName);
            case 'approve':
                return this.confirmApproval(count, entityName);
            case 'export':
                return this.confirmExport(count);
            case 'deactivate':
                return this.confirmDeactivation(count, entityName);
            case 'reset_password':
                return this.confirmPasswordReset(entityName);
            // For direct actions (view, edit)
            case 'edit':
            case 'view':
                return Promise.resolve({ isConfirmed: true });
            default:
                return Promise.resolve({ isConfirmed: true });
        }
    }
};