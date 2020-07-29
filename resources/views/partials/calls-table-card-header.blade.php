<div class="card-header">
    <div class="d-flex justify-content-between align-items-center h-1 cursor-pointer">
        <div class="status d-flex align-items-center justify-content-center">
            <span class="badge badge-outline-success d-flex align-items-center justify-content-center mr-1">
                <svg width="0.9rem" height="0.9rem" viewBox="0 0 16 16" class="bi bi-telephone mr-1" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M3.925 1.745a.636.636 0 0 0-.951-.059l-.97.97c-.453.453-.62 1.095-.421 1.658A16.47 16.47 0 0 0 5.49 10.51a16.471 16.471 0 0 0 6.196 3.907c.563.198 1.205.032 1.658-.421l.97-.97a.636.636 0 0 0-.06-.951l-2.162-1.682a.636.636 0 0 0-.544-.115l-2.052.513a1.636 1.636 0 0 1-1.554-.43L5.64 8.058a1.636 1.636 0 0 1-.43-1.554l.513-2.052a.636.636 0 0 0-.115-.544L3.925 1.745zM2.267.98a1.636 1.636 0 0 1 2.448.153l1.681 2.162c.309.396.418.913.296 1.4l-.513 2.053a.636.636 0 0 0 .167.604L8.65 9.654a.636.636 0 0 0 .604.167l2.052-.513a1.636 1.636 0 0 1 1.401.296l2.162 1.681c.777.604.849 1.753.153 2.448l-.97.97c-.693.693-1.73.998-2.697.658a17.47 17.47 0 0 1-6.571-4.144A17.47 17.47 0 0 1 .639 4.646c-.34-.967-.035-2.004.658-2.698l.97-.969z" />
                </svg>
                <p class="mb-0" style="line-height:1rem">
                    {{ $calls_per_day['num_calls'] }}
                    <span class="description">
                        Calls
                    </span>
                </p>
            </span>
            <span class="badge badge-outline-warning d-flex align-items-center justify-content-center mr-1">
                <svg width="0.9rem" height="0.9rem" viewBox="0 0 16 16" class="bi bi-telephone-x  mr-1"
                    fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M3.925 1.745a.636.636 0 0 0-.951-.059l-.97.97c-.453.453-.62 1.095-.421 1.658A16.47 16.47 0 0 0 5.49 10.51a16.47 16.47 0 0 0 6.196 3.907c.563.198 1.205.032 1.658-.421l.97-.97a.636.636 0 0 0-.06-.951l-2.162-1.682a.636.636 0 0 0-.544-.115l-2.052.513a1.636 1.636 0 0 1-1.554-.43L5.64 8.058a1.636 1.636 0 0 1-.43-1.554l.513-2.052a.636.636 0 0 0-.115-.544L3.925 1.745zM2.267.98a1.636 1.636 0 0 1 2.448.153l1.681 2.162c.309.396.418.913.296 1.4l-.513 2.053a.636.636 0 0 0 .167.604L8.65 9.654a.636.636 0 0 0 .604.167l2.052-.513a1.636 1.636 0 0 1 1.401.296l2.162 1.681c.777.604.849 1.753.153 2.448l-.97.97c-.693.693-1.73.998-2.697.658a17.471 17.471 0 0 1-6.571-4.144A17.47 17.47 0 0 1 .639 4.646c-.34-.967-.035-2.004.658-2.698l.97-.969zm7.879-.834a.5.5 0 0 1 .708 0L13 2.293 15.146.146a.5.5 0 0 1 .708.708L13.707 3l2.147 2.146a.5.5 0 0 1-.708.708L13 3.707l-2.146 2.147a.5.5 0 0 1-.708-.708L12.293 3 10.146.854a.5.5 0 0 1 0-.708z" />
                </svg>
                <p class="mb-0" style="line-height:1rem">
                    {{ $calls_per_day['unrated_calls'] }}
                    <span class="description">
                        Unrated Calls
                    </span>
                </p>
            </span>

            <span class="badge badge-outline-danger d-flex align-items-center justify-content-center">
                <svg width="0.9rem" height="0.9rem" viewBox="0 0 16 16" class="bi bi-telephone mr-1" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                    <path fill-rule="evenodd"
                        d="M4.285 12.433a.5.5 0 0 0 .683-.183A3.498 3.498 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.498 4.498 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683z" />
                    <path
                        d="M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z" />

                </svg>
                <p class="mb-0" style="line-height:1rem">
                    {{ $calls_per_day['failed_rating'] }}
                    <span class="description"> Failed Rating</span>
                </p>
            </span>
        </div>

        @if($loop->index == 0)
            <a class="text-dark" id="hard-refresh">
                <div class="d-flex align-items-center justify-content-center p-1 badge badge-outline-dark">
                    <svg width="1.2rem" height="1.2rem" viewBox="0 0 16 16" class="bi bi-arrow-clockwise"
                        fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M3.17 6.706a5 5 0 0 1 7.103-3.16.5.5 0 1 0 .454-.892A6 6 0 1 0 13.455 5.5a.5.5 0 0 0-.91.417 5 5 0 1 1-9.375.789z" />
                        <path fill-rule="evenodd"
                            d="M8.147.146a.5.5 0 0 1 .707 0l2.5 2.5a.5.5 0 0 1 0 .708l-2.5 2.5a.5.5 0 1 1-.707-.708L10.293 3 8.147.854a.5.5 0 0 1 0-.708z" />
                    </svg>
                </div>
            </a>

            <div class="sk-chase mr-1" id="loading" style="display: none;">
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
                <div class="sk-chase-dot"></div>
            </div>
        @endif

    </div>
</div>
