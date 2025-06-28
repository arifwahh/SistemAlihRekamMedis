// f1.js - Multi-step form controller
$(document).ready(function() {
    // Initialize all multi-step forms
    $('.f1').each(function() {
        var $f1 = $(this);
        var $steps = $f1.find('.f1-step');
        var $fieldsets = $f1.find('fieldset');
        
        // Hide all fieldsets except the first one
        $fieldsets.not(':first').hide();
        
        // Next button click handler
        $f1.find('.btn-next').click(function() {
            var $currentFieldset = $(this).closest('fieldset');
            var $nextFieldset = $currentFieldset.next('fieldset');
            
            if ($nextFieldset.length) {
                // Validate current step before proceeding
                if (validateStep($currentFieldset)) {
                    $currentFieldset.hide();
                    $nextFieldset.show();
                    
                    // Update step indicator
                    var currentStep = $f1.find('.f1-step.active');
                    currentStep.removeClass('active');
                    currentStep.next('.f1-step').addClass('active');
                    
                    // Update progress bar
                    var progress = (currentStep.index() + 1) / $steps.length * 100;
                    $f1.find('.f1-progress-line').css('width', progress + '%');
                }
            }
        });
        
        // Previous button click handler
        $f1.find('.btn-previous').click(function() {
            var $currentFieldset = $(this).closest('fieldset');
            var $prevFieldset = $currentFieldset.prev('fieldset');
            
            if ($prevFieldset.length) {
                $currentFieldset.hide();
                $prevFieldset.show();
                
                // Update step indicator
                var currentStep = $f1.find('.f1-step.active');
                currentStep.removeClass('active');
                currentStep.prev('.f1-step').addClass('active');
                
                // Update progress bar
                var progress = (currentStep.index() - 1) / $steps.length * 100;
                $f1.find('.f1-progress-line').css('width', progress + '%');
            }
        });
        
        // Submit button handler
        $f1.find('.btn-submit').click(function(e) {
            e.preventDefault();
            if (validateStep($(this).closest('fieldset'))) {
                $f1.submit();
            }
        });
    });
    
    // Function to validate current step
    function validateStep($fieldset) {
        var isValid = true;
        
        // Check all required fields in current step
        $fieldset.find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Special validation for date fields
        $fieldset.find('input[type="date"]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            }
        });
        
        if (!isValid) {
            alert('Harap lengkapi semua field yang wajib diisi');
        }
        
        return isValid;
    }
    
    // Dynamic field addition for visit data
    $(document).on('click', '#add', function() {
        var html = '<tr>' +
            '<td class="col-md-1"><input type="date" name="tanggalkunjungan[]" class="form-control nilai_list" required /></td>' +
            '<td class="col-md-3"><input type="text" name="keluhankunjungan[]" class="form-control nilai_list" placeholder="Diagnosa" required /></td>' +
            '<td class="col-md-1"><input type="text" name="polikunjungan[]" class="form-control nilai_list" placeholder="Poli" required /></td>' +
            '<td class="col-md-2"><input type="text" name="klinikkunjungan[]" class="form-control nilai_list" placeholder="Klinik" required /></td>' +
            '<td class="col-md-2"><select name="biaya[]" class="form-control" required><option value="BPJS">BPJS</option><option value="Umum">Umum</option></select></td>' +
            '<td class="col-md-3"><input type="text" name="nobpjs[]" class="form-control nilai_list" placeholder="No BPJS (Kosongi jika Umum)" /></td>' +
            '<td class="col-md-3"><button type="button" class="btn btn-danger remove">Remove</button></td>' +
            '</tr>';
        $('#dynamic_field').append(html);
    });
    
    $(document).on('click', '.remove', function() {
        $(this).closest('tr').remove();
    });
});