@extends('layouts.admin')

@section('title', 'Feedback & Suggestions - Tikako')

@section('content')

    <h1 class="display-6 fw-bold mb-4">Feedback & Suggestions</h1>
    <p class="text-muted">List of inputs and reviews from customers.</p>

    {{-- Summary Statistics --}}
    <div class="row mb-4">
        {{-- Average Rating Card --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-warning bg-gradient text-dark">
                <div class="card-body d-flex align-items-center">
                    <div class="fs-1 me-3"><i class="bi bi-star-fill text-white"></i></div>
                    <div>
                        <h6 class="text-uppercase small fw-bold mb-0">Average Rating</h6>
                        <h2 class="fw-bold mb-0">
                            {{ number_format($feedbacks->avg('rating'), 1) }} <small class="fs-6">/ 5.0</small>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Feedback Card --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="fs-1 me-3"><i class="bi bi-chat-quote-fill text-white-50"></i></div>
                    <div>
                        <h6 class="text-uppercase small fw-bold mb-0">Total Feedback</h6>
                        <h2 class="fw-bold mb-0">{{ $feedbacks->total() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Feedback Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4" style="width: 20%;">Customer</th>
                            <th style="width: 15%;">Rating</th>
                            <th style="width: 50%;">Message</th>
                            <th style="width: 15%;">Date</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($feedbacks as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $item->name }}</div>
                                    <div class="small text-muted">{{ $item->email ?? '-' }}</div>
                                </td>
                                
                                <td>
                                    {{-- Star Rating Logic --}}
                                    <div class="text-warning small">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $item->rating)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star text-muted opacity-25"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="small fw-bold text-dark mt-1">
                                        @if($item->rating == 5) Excellent
                                        @elseif($item->rating == 4) Good
                                        @elseif($item->rating == 3) Average
                                        @elseif($item->rating == 2) Poor
                                        @elseif($item->rating == 1) Terrible
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <p class="mb-0 text-secondary" style="font-style: italic;">
                                        "{{ Str::limit($item->message, 100) }}"
                                    </p>
                                    @if(strlen($item->message) > 100)
                                        <a href="#" class="small text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}">Read more</a>
                                        
                                        {{-- Read More Modal --}}
                                        <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">Message from {{ $item->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ $item->message }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                <td class="text-muted small">
                                    {{ $item->created_at->format('d M Y') }} <br>
                                    {{ $item->created_at->diffForHumans() }}
                                </td>

                                <td class="text-end pe-4">
                                    <form action="{{ route('admin.feedback.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Delete">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-chat-square-heart fs-1 opacity-25 mb-2 d-block"></i>
                                    No feedback available yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-end">
                {{ $feedbacks->links() }}
            </div>
        </div>
    </div>

@endsection