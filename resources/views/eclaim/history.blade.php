<div class="modal-body">
    <!-- Display Eclaim history -->
    <div>
        @if(!empty($eclaim->history))
            @php
                $history = json_decode($eclaim->history, true);
            @endphp
        <h4>{{ $history['username'] ?? '' }}</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $history['time'] ?? '' }}</td>
                        <td>{{ $history['comment'] ?? '' }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p>No history available</p>
        @endif
    </div>
</div>
