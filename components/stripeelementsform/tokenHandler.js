// The submit button gets disabled during validation
var $submit = $('#stripe-submit');
var oldSubmitText = $submit.text();

// This is the handler function that is called
// when the Stripe token has ben successfully generated.
function stripeTokenHandler (token) {
    $.request('{{ __SELF__ }}::onSubmit', {
        data: {token},
        loading: $.oc.stripeLoadIndicator,
        complete: function () {
            $.oc.stripeLoadIndicator.hide();
            $submit.prop('disabled', false).text(oldSubmitText);
        },
        success: function (results) {
            results.forEach(function (result) {
                if (result.hasOwnProperty('redirect')) {
                    document.location.href = result.redirect;
                }
            });
            console.log(results);
        },
        error: function (result) {
            $.oc.stripeLoadIndicator.hide();

            return $.oc.flashMsg({text: 'Failed to charge your card!', class: 'error'});
        }
    })
}