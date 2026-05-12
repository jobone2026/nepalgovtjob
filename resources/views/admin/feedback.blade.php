@extends('layouts.admin')

@section('title', 'User Feedback')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">User Feedback</h1>
            <button onclick="refreshFeedback()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="feedbackTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody id="feedbackList" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading feedback...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function refreshFeedback() {
    const feedbackList = document.getElementById('feedbackList');
    feedbackList.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>';
    
    fetch('/admin/feedback/list')
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                feedbackList.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No feedback received yet.</td></tr>';
                return;
            }
            
            let html = '';
            data.forEach(item => {
                const typeColors = {
                    bug: 'bg-red-100 text-red-800',
                    feature: 'bg-blue-100 text-blue-800',
                    general: 'bg-green-100 text-green-800'
                };
                
                const typeIcons = {
                    bug: 'fa-bug',
                    feature: 'fa-lightbulb',
                    general: 'fa-comment'
                };
                
                html += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-semibold ${typeColors[item.type] || 'bg-gray-100 text-gray-800'}">
                                <i class="fas ${typeIcons[item.type] || 'fa-comment'}"></i>
                                ${item.type.toUpperCase()}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-md">${item.message}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            ${item.email && item.email !== 'anonymous' ? item.email : '<span class="text-gray-400">Anonymous</span>'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            ${item.ip}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            ${item.page_url ? `<a href="${item.page_url}" target="_blank" class="text-blue-600 hover:underline" title="${item.page_url}">${item.page_url.replace('https://jobone.in', '').substring(0, 30)}...</a>` : '-'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${item.timestamp}
                        </td>
                    </tr>
                `;
            });
            
            feedbackList.innerHTML = html;
        })
        .catch(error => {
            feedbackList.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error loading feedback</td></tr>';
            console.error('Error:', error);
        });
}

// Load on page load
refreshFeedback();
</script>
@endsection
