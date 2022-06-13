<div>
    <div>
        <div class="h-auto mx-auto grid grid-cols-7 rounded max-w-xl">
            @foreach($dates as $key => $date)
                <div class="">
                    <div class="h-12 text-center flex flex-wrap justify-center content-center mb-2">
                        <div class="flex flex-col">
                            <div
                                class="font-montserrat font-bold sm:text-xl text-base sm:tracking-wide">{{ $date }}
                                .
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col marked">
                        @foreach($hours as $hour)
                            <span wire:click="select('{{ $key }}','{{$hour}}')"
                                  class="btn  @if(!in_array($hour,$schedule[$key])) bg-gray-300  @else bg-cyan-500 text-white  @endif sm:px-2 sm:py-1 p-1 rounded-xl text-center mt-1 w-auto mx-auto font-maven font-bold cursor-pointer text-sm sm:text-base">
                            {{$hour}}
                        </span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div id="more" wire:click="save()"
             class="flex justify-center w-24 bg-cyan-600 rounded-full mt-3 text-white font-montserrat cursor-pointer font-bold px-1 py-2 min-w-[10rem] mx-auto">
            Enregistrer
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
                });
                this.classList.add('bg-pink', 'text-white');
            }

            document.querySelectorAll('.btn').forEach(button => {
                button.onclick = buttonClicked;
            });
        </script>
    </div>

</div>

