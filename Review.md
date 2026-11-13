# Part-A => Code-based Issues

# 1. Fetching every single order.No limit is settled.
# 2. PHP filtering inside the loop wastes resources completely.
# 3. n+1 query accessing users and ticket relationship can generate additional DB queries for every single order.
# 4. $order->tickets->count() =>Unnecessary ticket loading in memory is happening jut for "counting"
# 5. 	$order->tickets->sum('price') =>Unnecessary ticket loading in memory t calculate just total.
# 6. Pagination is not implemented which slowdwn the application while ftching all the orders.





