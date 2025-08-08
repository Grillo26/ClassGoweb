 <div class="am-activity_data">
                        <div class="am-activity_data_item">
                            <div class="am-activity_data_user">
                                <span>
                                    {{ $tutor_name }}
                                    <strong>{{ $this->tutorsCount }}</strong>
                                </span>
                                <a href="{{ route('admin.users', ['role' => 'tutor']) }}" target="_blank"
                                    class="am-activity_data_user_link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"
                                        fill="none">
                                        <g opacity="0.6">
                                            <path
                                                d="M3.16675 13.8327L13.8334 3.16602M13.8334 3.16602V10.4993M13.8334 3.16602H6.50008"
                                                stroke="black" stroke-linecap="round" stroke-linejoin="round" />
                                        </g>
                                    </svg>
                                </a>
                            </div>
                            @if ($this->tutors->isNotEmpty())
                            <div class="am-activity_data_user_img">
                                <figure>
                                    @foreach ($this->tutors as $tutor)
                                    @if (!empty($tutor->profile?->image) &&
                                    Storage::disk(getStorageDisk())->exists($tutor->profile?->image))
                                    <img src="{{ url(Storage::url($tutor->profile?->image)) }}"
                                        alt="{{ $tutor->profile?->image }}">
                                    @else
                                    <img src="{{ resizedImage('placeholder.png',34,34) }}"
                                        alt="{{ $tutor->profile?->image }}" />
                                    @endif
                                    @endforeach
                                </figure>
                            </div>
                            @endif
                        </div>
                        <div class="am-activity_data_item">
                            <div class="am-activity_data_user">
                                <span>
                                    {{ $student_name }}
                                    <strong>{{ $this->studentsCount }}</strong>
                                </span>

                                <a href="{{ route('admin.users', ['role' => 'student']) }}" target="_blank"
                                    class="am-activity_data_user_link">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"
                                        fill="none">
                                        <g opacity="0.6">
                                            <path
                                                d="M3.16675 13.8327L13.8334 3.16602M13.8334 3.16602V10.4993M13.8334 3.16602H6.50008"
                                                stroke="black" stroke-linecap="round" stroke-linejoin="round" />
                                        </g>
                                    </svg>
                                </a>
                            </div>
                            @if ($this->students->isNotEmpty())
                            <div class="am-activity_data_user_img">
                                <figure>
                                    @foreach ($this->students as $student)
                                    @if (!empty($student->profile?->image) &&
                                    Storage::disk(getStorageDisk())->exists($student->profile?->image))
                                    <img src="{{ url(Storage::url($student->profile?->image)) }}"
                                        alt="{{ $student->profile?->image }}">
                                    @else
                                    <img src="{{ resizedImage('placeholder.png',34,34) }}"
                                        alt="{{ $student->profile?->image }}" />
                                    @endif
                                    @endforeach
                                </figure>
                            </div>
                            @endif
                        </div>
                    </div>