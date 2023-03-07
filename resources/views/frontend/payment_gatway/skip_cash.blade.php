<form method="POST" action="{{ route('payment.skipcash.pay') }}">
    @csrf
    <div>
        <label for="amount">Amount</label>
        <input type="number" id="amount" value={{$data['amount']}} name="amount">
        <input type="hidden" id="order_id" value={{$data['order_number']}} name="order_id">

    </div>
    <div>
        <label for="currency">Currency</label>
        <select id="currency" name="currency" required>
            <option value="USD">USD</option>
            <option value="EUR">EUR</option>
            <option value="GBP">GBP</option>
        </select>
    </div>
    <div>
        <label for="reference_id">Reference ID</label>
        <input type="text" id="reference_id" name="reference_id" value={{$data['order_number']}} required>
    </div>
    <div>
        
    </div>
    <button type="submit">Pay Now</button>
</form>
