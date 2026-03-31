#!/bin/bash
BASE_URL="http://local.api.valley.newamericanhistory.org/api"

echo "Testing API endpoints..."

# Test 1: Basic index
curl -s "$BASE_URL/letters" | jq -e '.data' > /dev/null && echo "✓ Letters index" || echo "✗ Letters index"

# Test 2: Exact filter
curl -s "$BASE_URL/letters?filter[county]=augusta" | jq -e '.data[0].county' > /dev/null && echo "✓ Exact filter" || echo "✗ Exact filter"

# Test 3: Date filter (year only)
curl -s "$BASE_URL/letters?filter[date]=1863" | jq -e '.data' > /dev/null && echo "✓ Date filter (year)" || echo "✗ Date filter (year)"

# Test 4: Date filter (year-month)
curl -s "$BASE_URL/letters?filter[date]=1863-07" | jq -e '.data' > /dev/null && echo "✓ Date filter (year-month)" || echo "✗ Date filter (year-month)"

# Test 5: Numeric comparison
curl -s "$BASE_URL/population-census?filter[age:gt]=18" | jq -e '.data' > /dev/null && echo "✓ Numeric filter" || echo "✗ Numeric filter"

# Test 6: Text search
curl -s "$BASE_URL/letters?filter[q]=home" | jq -e '.data' > /dev/null && echo "✓ Text search" || echo "✗ Text search"

# Test 7: Sorting
curl -s "$BASE_URL/letters?sort=-date" | jq -e '.data[0].date' > /dev/null && echo "✓ Sorting" || echo "✗ Sorting"

# Test 8: Show endpoint
curl -s "$BASE_URL/letters/aug-let-001" | jq -e '.data.valley_id' > /dev/null && echo "✓ Show endpoint" || echo "✗ Show endpoint"

# Test 9: TEI XML processing
curl -s "$BASE_URL/letters/aug-let-001" | jq -e '.data.body' > /dev/null && echo "✓ TEI processing" || echo "✗ TEI processing"

# Test 10: CORS headers
curl -I "$BASE_URL/letters" | grep -q "access-control-allow-origin" && echo "✓ CORS headers" || echo "✗ CORS headers"

echo "Test suite complete!"
