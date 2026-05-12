@extends('layouts.admin')

@section('title', 'WhatsApp Share')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">📱 Share Posts to WhatsApp</h1>
            <a href="{{ \App\Models\SiteSetting::where('key', 'whatsapp_channel')->value('value') ?: 'https://whatsapp.com/channel/0029VbD9cau2P59hFZ1nwh22' }}" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                Open WhatsApp Channel
            </a>
        </div>

        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="font-semibold text-blue-800 mb-2">📝 How to Share:</h3>
            <ol class="list-decimal list-inside text-sm text-blue-700 space-y-1">
                <li>Click "Generate Message" button next to any post</li>
                <li>Copy the generated message</li>
                <li>Click "Share to WhatsApp" to open WhatsApp</li>
                <li>Paste and send to your channel or groups</li>
            </ol>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Post</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($posts as $post)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ Str::limit($post->title, 60) }}</div>
                            @if($post->total_vacancies)
                            <div class="text-xs text-gray-500">Vacancies: {{ $post->total_vacancies }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($post->type === 'job') bg-blue-100 text-blue-800
                                @elseif($post->type === 'result') bg-green-100 text-green-800
                                @elseif($post->type === 'admit_card') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $post->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $post->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button onclick="generateMessage({{ $post->id }})" 
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded mr-2">
                                Generate Message
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>

<!-- Modal -->
<div id="messageModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">WhatsApp Message</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Message Preview:</label>
            <textarea id="messageText" rows="12" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md font-mono text-sm"
                readonly></textarea>
        </div>

        <div class="flex gap-2">
            <button onclick="copyMessage()" 
                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                📋 Copy Message
            </button>
            <a id="whatsappLink" href="#" target="_blank"
                class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-center">
                📱 Share to WhatsApp
            </a>
        </div>
    </div>
</div>

<script>
function generateMessage(postId) {
    fetch(`/admin/whatsapp/generate/${postId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('messageText').value = data.message;
            document.getElementById('whatsappLink').href = data.link;
            document.getElementById('messageModal').classList.remove('hidden');
        })
        .catch(error => {
            alert('Error generating message: ' + error);
        });
}

function closeModal() {
    document.getElementById('messageModal').classList.add('hidden');
}

function copyMessage() {
    const messageText = document.getElementById('messageText');
    messageText.select();
    document.execCommand('copy');
    
    // Show feedback
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '✅ Copied!';
    setTimeout(() => {
        btn.innerHTML = originalText;
    }, 2000);
}

// Close modal when clicking outside
document.getElementById('messageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection

