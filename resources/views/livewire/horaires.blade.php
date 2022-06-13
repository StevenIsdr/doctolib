<div>
        <div>
            <div class="flex flex-wrap mb-2 lg:gap-40 sm:gap-24 gap-6 justify-center">
                @if($isAfter)
                    <span wire:click="removeSemaine">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-10 cursor-pointer border-2 border-lightsky-200 text-gray rounded-full p-2"
                         viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd"/>
                    </svg>
            </span>
                @else
                    <span wire:click="">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-10 cursor-pointer border-2 border-gray text-gray rounded-full p-2" viewBox="0 0 20 20"
                     fill="currentColor">
                      <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd"/>
                    </svg>
            </span>
                @endif
                <div class="text-2xl text-gray font-montserrat font-bold tracking-wide">
                    Semaine {{ $dates[0]->weekOfYear }}</div>
                <span wire:click="addSemaine">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-10 cursor-pointer border-2 border-lightsky-200 text-gray rounded-full p-2" viewBox="0 0 20 20"
                 fill="currentColor">
              <path fill-rule="evenodd"
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"/>
            </svg>
        </span>
            </div>
            <div class="h-auto mx-auto grid grid-cols-5 rounded max-w-xl">
                @foreach($dates as $date)
                    @php($date = Carbon\Carbon::create($date))
                    <div class="">
                        <div class="h-12 text-center flex flex-wrap justify-center content-center mb-2">
                            <div class="flex flex-col">
                                <div
                                    class="font-montserrat font-bold text-xl tracking-wide">{{ ucfirst(substr($date->dayName, 0, 3).'.')}}
                                </div>
                                <div
                                    class="font-montserrat font-light text-gray text-sm tracking-wide">{{ $date->day.' '.ucfirst($date->monthName) }}</div>
                            </div>
                        </div>
                        <div class="flex flex-col marked max-h-[22rem] overflow-y-hidden">
                            @forelse($hours[$date->format('Ymd')] as $hour)
                                <span
                                    class="btn p-2 hover:bg-cyan-300 bg-cyan-200 rounded-xl text-center mt-1 lg:w-24 sm:w-16 w-14  mx-auto font-montserrat font-bold cursor-pointer text-sm sm:text-base"
                                    @click="timestamp = '{{$date->format('Y-m-d')}} {{$hour}}'">
                                    {{$hour}}
                                </span>
                            @empty
                                <span
                                    class="text-center mt-1 lg:w-24 sm:w-16 w-14 mx-auto font-bold lg:text-lg sm:text-sm text-xs text-red-500">
                                    Aucun horaire disponible
                                </span>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="more"
                 class="flex justify-center w-24 bg-cyan-600 rounded-full mt-3 text-white font-montserrat cursor-pointer font-bold px-1 py-2 min-w-[10rem] mx-auto"
                 onclick="more()">Voir plus
            </div>

            <script>
                let isChecked = false;

                function more() {
                    const moreButton = document.getElementById("more");
                    if (!isChecked) {
                        const elements = document.querySelectorAll('.marked');
                        Array.from(elements).forEach((element, index) => {
                            element.classList.remove('max-h-[22rem]', "overflow-y-hidden");
                        });
                        moreButton.innerText = "Voir moins";
                        isChecked = true;
                    } else {
                        const elements = document.querySelectorAll('.marked');
                        Array.from(elements).forEach((element, index) => {
                            element.classList.add('max-h-[22rem]', "overflow-y-hidden");
                        });
                        moreButton.innerText = "Voir plus";
                        isChecked = false;
                    }
                }

                function buttonClicked() {
                    document.querySelectorAll('.btn').forEach(button => {
                        button.classList.remove('bg-pink', 'text-white');
                        button.classList.add('hover:bg-lightsky-300');
                    });
                    this.classList.add('bg-cyan-500', 'text-white');
                    this.classList.remove('hover:bg-cyan-300');
                }

                document.querySelectorAll('.btn').forEach(button => {
                    button.onclick = buttonClicked;
                });
            </script>
        </div>

</div>
