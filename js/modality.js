document.addEventListener('DOMContentLoaded', function() {
    function handleBuyFormSubmission(productId, quantity) {
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);

        fetch('script/checkout-process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            console.log(data);
            $('#buyModal').modal('hide');
            $('#thankYouModal').modal('show');
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    }

    $(document).on('click', '.buy-btn', function(e) {
        e.preventDefault();
    
        var productId = $(this).data('product-id');
        var productName = $(this).closest('.product-container').find('.product-details h4').text();
        var productPrice = $(this).closest('.product-container').find('.product-details p').text();
        var productImage = $(this).closest('.product-container').find('img').attr('src');
    
        var modalBody = $('#buyModalBody');
        modalBody.html(`
            <div class="product-modal">
                <img src="${productImage}" alt="${productName}">
                <div>
                    <h4>${productName}</h4>
                    <p>${productPrice}</p>
                    <form id="buyProductForm_${productId}" class="buy-product-form" data-product-id="${productId}">
                        <label for="quantity_${productId}">Quantity:</label>
                        <input type="number" name="quantity" min="1" value="1" id="quantity_${productId}" required>
                        <button type="submit" class="btn btn-success">Buy</button>
                    </form>
                </div>
            </div>
        `);
    
        $("#buyProductForm_"+productId).on("submit", function(e){
            e.preventDefault();
            var quantity = $(this).find("#quantity_"+productId).val();
            handleBuyFormSubmission(productId, quantity);
        });
    
        $('#buyModal').modal('show');
    });
// Add event listener for the thank you modal dismissal
$('#thankYouModal').on('click', function(event) {
    if (event.target === this || $(event.target).attr('data-dismiss') === 'modal') {
        $('#thankYouModal').modal('hide');
    }
});
});