@extends('layouts.app')

@section('content')
<div x-data="game()" class=" px-10 flex items-center justify-center min-h-screen">
    <h1 class="fixed top-0 right-0 p-10 font-bold">
        <span x-text="points"></span>
        <span class=" text-xs">pts</span>
    </h1>
    <div class="flex-1 grid grid-cols-4 gap-10">
        <template x-for="card in cards">
            <div>
                <button x-show="! card.cleared" :style="'background:' + (card.flipped ? card.color : '#999')"
                    class="h-32 w-full" @click=" flipCard(card)">
                </button>
            </div>

        </template>

    </div>
</div>

{{-- flash  message --}}

<div x-data="{ show: false, message: 'Default message'}"
x-show.transition.opacity="show"
x-text="message"
@flash.window="
message = $event.detail.message;
show = true;
setTimeout(() => show = false, 1000)
"
class="fixed bottom-0 right-0 bg-green-500 text-white p-2 mb-4 rounded"
>
</div>

@endsection

@section('extra-js')
<script>
    function pause(milliseconds = 1000) {
        return new Promise(resolve => setTimeout(resolve, milliseconds));
    }

    function flash (message) {
        window.dispatchEvent(new CustomEvent('flash', {
            detail: { message }
        }));
    }

    function game() {
        return {
            cards: [ 
                {color: 'green', flipped: false, cleared: false },
                {color: 'red', flipped: false, cleared: false },
                {color: 'blue', flipped: false, cleared: false },
                {color: 'orange', flipped: false, cleared: false },
                {color: 'green', flipped: false, cleared: false },
                {color: 'red', flipped: false, cleared: false },
                {color: 'blue', flipped: false, cleared: false },
                {color: 'orange', flipped: false, cleared: false },
            ],

            get flippedCards(){
                return this.cards.filter(card => card.flipped);
            },

            get clearedCards() {
                return this.cards.filter(card => card.cleared);
            },

            get remainingCards() {
                return this.cards.filter(card => ! card.cleared);
            },

            get points() {
                return this.clearedCards.length;
            },

            async flipCard(card) {
                if (this.flippedCards.length === 2) {
                    return;
                }

                card.flipped = ! card.flipped;

                if (this.flippedCards.length === 2) {
                    if (this.hasMatch()) {
                        flash('You found the match!');
                        await pause();
                        
                        this.flippedCards.forEach(card => card.cleared= true);
                        
                        // is the game over ?
                        //  if there are no remaining cards
                        if (! this.remainingCards.length) {
                            alert('You Won!');
                        }
                        
                    }
                    
                    await pause();
                 
                    this.flippedCards.forEach(card => card.flipped = false);
                }
            },

            hasMatch() {
                return this.flippedCards[0]['color'] === this.flippedCards[1]['color'];
            }
        };
    }

</script>
@endsection