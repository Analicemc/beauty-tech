window.System = ( function() {

    const initTwoFactorEmailForm = function()
    {
        $(document).ready(function() {
            const inputs = $('#two-factor-container input');
            const emailCode = $('#email_code');
            const resendLink = $('#resend-link');
            const verifyBtn = $('#btn-two-factor');
            
            inputs.on('input', function() {
                const index = inputs.index(this);
                const value = $(this).val();
                
                $(this).val(value.replace(/[^0-9]/g, ''));
                
                if (value.length === 1 && index < inputs.length - 1) {
                    $(inputs[index + 1]).focus();
                }
                
                updateCode();
                checkComplete();
            });
            
            inputs.on('keydown', function(e) {
                const index = inputs.index(this);
                
                if (e.key === 'Backspace' && $(this).val() === '') {
                    if (index > 0) {
                        e.preventDefault();
                        $(inputs[index - 1]).focus().val('');
                        updateCode();
                        checkComplete();
                    }
                }
            });
            
            inputs.on('paste', function(e) {
                e.preventDefault();

                const pasteData = e.originalEvent.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                
                if (pasteData.length === 6) {
                    inputs.each(function(i) {
                        $(this).val(pasteData[i] || '');
                    });
                    updateCode();
                    checkComplete();
                }
            });
            
            function updateCode() {
                let code = '';
                inputs.each(function() {
                    code += $(this).val();
                });
                emailCode.val(code);
            }
            
            function checkComplete() {
                const complete = Array.from(inputs).every(input => input.value.length === 1);
                verifyBtn.prop('disabled', !complete);
            }
            
            let countdown = 60;
            let canResend = true;
            
            resendLink.on('click', function() {
                if (!canResend) return;
                
                canResend = false;
                resendLink.css({
                    'opacity': '0.5',
                    'cursor': 'default'
                });
                
                resendLink.text(`Aguarde 60s`);
                const timer = setInterval(() => {
                    countdown--;
                    resendLink.text(`Aguarde ${countdown}s`);
                    
                    if (countdown <= 0) {
                        clearInterval(timer);
                        countdown = 60;
                        canResend = true;
                        resendLink.text('Reenviar código');
                        resendLink.css({
                            'opacity': '1',
                            'cursor': 'pointer'
                        });
                    }
                }, 1000);
            });
            
            $(inputs[0]).focus();
            checkComplete();
        });
    }

    const initTwoFactorGoogleForm = function()
    {
        $(document).ready(function() {
            const inputs = $('#two-factor-container input');
            const googleCode = $('#google_code');
            const verifyBtn = $('#btn-two-factor');
            
            inputs.on('input', function() {
                const index = inputs.index(this);
                const value = $(this).val();
                
                $(this).val(value.replace(/[^0-9]/g, ''));
                
                if (value.length === 1 && index < inputs.length - 1) {
                    $(inputs[index + 1]).focus();
                }
                
                updateCode();
                checkComplete();
            });
            
            inputs.on('keydown', function(e) {
                const index = inputs.index(this);
                
                if (e.key === 'Backspace' && $(this).val() === '') {
                    if (index > 0) {
                        e.preventDefault();
                        $(inputs[index - 1]).focus().val('');
                        updateCode();
                        checkComplete();
                    }
                }
            });
            
            inputs.on('paste', function(e) {
                e.preventDefault();
                const pasteData = e.originalEvent.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                
                if (pasteData.length === 6) {
                    inputs.each(function(i) {
                        $(this).val(pasteData[i] || '');
                    });
                    updateCode();
                    checkComplete();
                }
            });
            
            function updateCode() {
                let code = '';
                inputs.each(function() {
                    code += $(this).val();
                });
                googleCode.val(code);
            }
            
            function checkComplete() {
                const complete = Array.from(inputs).every(input => input.value.length === 1);
                verifyBtn.prop('disabled', !complete);
            }
            
            $(inputs[0]).focus();
            checkComplete();
        });
    }

    return {
        initTwoFactorEmailForm: initTwoFactorEmailForm,
        initTwoFactorGoogleForm:initTwoFactorGoogleForm
    };

})();