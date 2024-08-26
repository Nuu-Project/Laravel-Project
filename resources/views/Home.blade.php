<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="images/icon.png">
        <title>聯大二手書交易平台</title>
        <link rel="stylesheet" href="css/tailwind.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" integrity="sha512-7x3zila4t2qNycrtZ31HO0NnJr8kg2VI67YLoRSyi9hGhRN66FHYWr7Axa9Y1J9tGYHVBPqIjSE1ogHrJTz51g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> 
    </head>

    <body class="font-body">

        <!-- home section -->
        <section class="bg-white py-10 md:mb-10">

            <div class="container max-w-screen-xl mx-auto px-4">

                <nav class="flex-wrap lg:flex items-center" x-data="{navbarOpen:false}">
                    <div class="flex items-center mb-10 lg:mb-0">
                        <img src="images/book-4-fix.png" alt="Logo">

                        <button class="lg:hidden w-10 h-10 ml-auto flex items-center justify-center border border-blue-500 text-blue-500 rounded-md" @click="navbarOpen = !navbarOpen">
                            <i data-feather="menu"></i>
                        </button>
                    </div>

                    <ul class="lg:flex flex-col lg:flex-row lg:items-center lg:mx-auto lg:space-x-8 xl:space-x-14" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
                        <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
                            <a href="/">首頁</a>
                        </li>
                        <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
                            <a href="/store">商品</a>
                        </li>
                        <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
                            <a href="/add">刊登</a>
                        </li>
                        <li class="font-semibold text-gray-900 hover:text-gray-400 transition ease-in-out duration-300 mb-5 lg:mb-0 text-2xl">
                            <a href="/check">我的商品</a>
                        </li>
                    </ul>

                    <div class="lg:flex flex-col md:flex-row md:items-center text-center md:space-x-6" :class="{'hidden':!navbarOpen,'flex':navbarOpen}">
                        <a href="/regist" class="px-6 py-4 bg-blue-500 text-white font-semibold text-lg rounded-xl hover:bg-blue-700 transition ease-in-out duration-500 mb-5 md:mb-0">註冊</a>

                        <a href="/login" class="px-6 py-4 border-2 border-blue-500 text-blue-500 font-semibold text-lg rounded-xl hover:bg-blue-700 hover:text-white transition ease-linear duration-500">登入</a>
                    </div>
                </nav>

                <div class="flex flex-col lg:flex-row justify-between space-x-20">
                    <div class="text-center lg:text-left mt-40">
                        <h1 class="font-semibold text-gray-900 text-3xl md:text-6xl leading-normal mb-6">  聯大資管系 <br> 二手書交易平台</h1>

                        <p class="font-dark text-gray-400 text-md md:text-lg leading-normal mb-12">我們致力打造出一個能系上同學更方便買書的平台</p>

                        <a href="/login" class="px-6 py-4 bg-info font-semibold text-white text-lg rounded-xl hover:bg-blue-700 transition ease-in-out duration-500">Get started</a>
                    </div>

                    <div class="mt-15 ">
                        <img class="" src="images/logofix.png" alt="Image">
                    </div>
                </div>

            </div> <!-- container.// -->

        </section>
        <!-- home section //end -->

        <!-- feature section -->
        <section class="bg-white py-1 md:mt-10">

            <div class="container max-w-screen-xl mx-auto px-4">

                <p class="font-black text-gray-500 text-lg md:text-xl text-center uppercase mb-6">平台理念</p>

                <h1 class="font-semibold text-gray-900 text-xl md:text-4xl text-center leading-normal mb-10">讓社恐和I人<br> 都能有個安靜的空間買書</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-20">
                    <div class="text-center">
                        <div class="flex justify-center mb-6">
                            <div class="w-20 py-6 flex justify-center bg-blue-200 bg-opacity-30 text-blue-700 rounded-xl">
                                <i data-feather="globe"></i>
                            </div>
                        </div>

                        <h4 class="font-semibold text-lg md:text-2xl text-gray-900 mb-6">簡單的操作介面</h4>

                        <p class="font-black text-gray-500 text-md md:text-xl mb-6">沒有繁瑣的流程，一切步驟都經過精簡</p>

                        <div class="flex justify-center">
                            <!-- <a href="#" class="flex items-center gap-5 px-6 py-4 font-semibold text-info text-lg rounded-xl hover:bg-info hover:text-white transition ease-linear duration-500">
                                Learn more 
                                <i data-feather="chevron-right"></i> -->
                            </a>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="flex justify-center mb-6">
                            <div class="w-20 py-6 flex justify-center bg-blue-200 bg-opacity-30 text-blue-700 rounded-xl">
                                <i data-feather="arrow-up-right"></i>
                            </div>
                        </div>

                        <h4 class="font-semibold text-lg md:text-2xl text-gray-900 mb-6">專為資管系服務的平台</h4>

                        <p class="font-black text-gray-500 text-md md:text-xl mb-6">在這裡刊登二手書的商品，都是資管系可能或必須用到的書</p>

                        <div class="flex justify-center">
                            <!-- <a href="#" class="flex items-center gap-5 px-6 py-4 font-semibold text-info text-lg rounded-xl hover:bg-info hover:text-white transition ease-linear duration-500">
                                Learn more 
                                <i data-feather="chevron-right"></i>
                            </a> -->
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="flex justify-center mb-6">
                            <div class="w-20 py-6 flex justify-center bg-blue-200 bg-opacity-30 text-blue-700 rounded-xl">
                                <i data-feather="clock"></i>
                            </div>
                        </div>

                        <h4 class="font-semibold text-lg md:text-2xl text-gray-900 mb-6">方便省時</h4>

                        <p class="font-black text-gray-500 text-md md:text-xl mb-6">每個的人時間都很寶貴，我們想讓大家用最快的速度買到自己需要的教科書</p>

                        <div class="flex justify-center">
                            <!-- <a href="#" class="flex items-center gap-5 px-6 py-4 font-semibold text-info text-lg rounded-xl hover:bg-info hover:text-white transition ease-linear duration-500">
                                Learn more 
                                <i data-feather="chevron-right"></i>
                            </a> -->
                        </div>
                    </div>
                </div>

            </div> <!-- container.// -->

        </section>
        <!-- feature section //end -->

        <!-- donation section -->
        <section class="bg-white py-16">

            <div class="container max-w-screen-xl mx-auto px-4">

                <h1 class="font-semibold text-gray-900 text-xl md:text-4xl text-center mb-16">推薦書籍</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="px-6 py-6 w-full border-2 border-gray-200 rounded-3xl">
                        <img src="images/book-5.jpg" alt="Image" class="mb-6 hover:opacity-75 transition ease-in-out duration-500">
        
                        <h4 class="font-semibold text-gray-900 text-lg md:text-2xl mb-6">統計學 <br></h4>
        
                        <!-- <p class="font-light text-gray-400 text-sm md:text-md lg:text-lg mb-10">Help flood victims in Indonesia by <br> donating sincerely, regardless of the...</p> -->
        
                        <div class="flex items-center justify-between mb-8">
                            <h6 class="font-black text-gray-600 text-sm md:text-lg">年級 : <span class="font-semibold text-gray-900 text-md md:text-lg">大二【上下】</span></h6>
        
                            <h6 class="font-black text-gray-600 text-sm md:text-lg">課程 : <span class="font-semibold text-gray-900 text-md md:text-lg">必修</span></h6>
                        </div>
        
                        <div class="hidden md:block lg:flex items-center justify-between mb-8">
                            <div>
                                <div class="w-72 h-2 bg-info opacity-10 rounded-lg absolute"></div>
        
                                <div class="w-40 h-2 bg-info rounded-lg relative"></div>
                            </div>
        
                            <p class="font-light text-gray-900 text-md">50%</p>
                        </div>

                        <a href="https://eshop.tsanghai.com.tw/products/ps0471pc" class="px-6 py-4 bg-info font-semibold text-white text-lg rounded-xl hover:bg-blue-700 transition ease-in-out duration-500">前往了解</a>                    </div>

                    <div class="px-6 py-6 w-full border-2 border-gray-200 rounded-3xl">
                        <img src="images/book-6.jpg" alt="Image" class="mb-6 hover:opacity-75 transition ease-in-out duration-500">
        
                        <h4 class="font-semibold text-gray-900 text-lg md:text-2xl mb-6">會計學<br></h4>
        
                        <!-- <p class="font-light text-gray-400 text-sm md:text-md lg:text-lg mb-10">Donation for COVID-19 in Indonesia by <br> donating sincerely, regardless of the...</p> -->
        
                        <div class="flex items-center justify-between mb-8">
                            <h6 class="font-black text-gray-600 text-sm md:text-lg">年級 : <span class="font-semibold text-gray-900 text-md md:text-lg">大一【上】</span></h6>
        
                            <h6 class="font-black text-gray-600 text-sm md:text-lg">課程 : <span class="font-semibold text-gray-900 text-md md:text-lg">必修</span></h6>
                        </div>
        
                        <div class="hidden md:block lg:flex items-center justify-between mb-8">
                            <div>
                                <div class="w-72 h-2 bg-info opacity-10 rounded-lg absolute"></div>
        
                                <div class="w-56 h-2 bg-info rounded-lg relative"></div>
                            </div>
        
                            <p class="font-light text-gray-900 text-md">80%</p>
                        </div>

                        <a href="https://www.tenlong.com.tw/products/9781119824237?list_name=srh" class="px-6 py-4 bg-info font-semibold text-white text-lg rounded-xl hover:bg-blue-700 transition ease-in-out duration-500">前往了解</a>
                    </div>

                    <div class="px-6 py-6 w-full border-2 border-gray-200 rounded-3xl">
                        <img src="images/book-7.jpg" alt="Image" class="mb-6 hover:opacity-75 transition ease-in-out duration-500">
        
                        <h4 class="font-semibold text-gray-900 text-lg md:text-2xl mb-6">計算機概論</h4>
        
                        <!-- <p class="font-light text-gray-400 text-sm md:text-md lg:text-lg mb-10">Help homeless cat and the oether <br> animals by donating sincerely regar...</p> -->
        
                        <div class="flex items-center justify-between mb-8">
                            <h6 class="font-black text-gray-600 text-sm md:text-lg">年級 : <span class="font-semibold text-gray-900 text-md md:text-lg">大一【上】</span></h6>
        
                            <h6 class="font-black text-gray-600 text-sm md:text-lg">課程 : <span class="font-semibold text-gray-900 text-md md:text-lg">必修</span></h6>
                        </div>
        
                        <div class="hidden md:block lg:flex items-center justify-between mb-8">
                            <div>
                                <div class="w-72 h-2 bg-info opacity-10 rounded-lg absolute"></div>
        
                                <div class="w-72 h-2 bg-info rounded-lg relative"></div>
                            </div>
        
                            <p class="font-light text-gray-900 text-md">100%</p>
                        </div>

                        <a href="https://www.tenlong.com.tw/products/9789579282543?list_name=srh" class="px-6 py-4 bg-info font-semibold text-white text-lg rounded-xl hover:bg-blue-700 transition ease-in-out duration-500">前往了解</a>
                    </div>

                    <!-- <div class="px-6 py-6 w-full border-2 border-gray-200 rounded-3xl">
                        <img src="images/donation-4.png" alt="Image" class="mb-6 hover:opacity-75 transition ease-in-out duration-500">
        
                        <h4 class="font-semibold text-gray-900 text-lg md:text-2xl mb-6">Help save poor children in <br> Pakistan</h4>
        
                        <p class="font-light text-gray-400 text-sm md:text-md lg:text-lg mb-10">Help save poor children in Pakistas by <br> donating sincerely, regardless of the...</p>
        
                        <div class="flex items-center justify-between mb-8">
                            <h6 class="font-light text-gray-400 text-sm md:text-lg">Goals : <span class="font-semibold text-gray-900 text-md md:text-lg">$75000</span></h6>
        
                            <h6 class="font-light text-gray-400 text-sm md:text-lg">Raised : <span class="font-semibold text-gray-900 text-md md:text-lg">$50000</span></h6>
                        </div>
        
                        <div class="hidden md:block lg:flex items-center justify-between mb-8">
                            <div>
                                <div class="w-72 h-2 bg-info opacity-10 rounded-lg absolute"></div>
        
                                <div class="w-48 h-2 bg-info rounded-lg relative"></div>
                            </div>
        
                            <p class="font-light text-gray-900 text-md">66%</p>
                        </div>

                        <button class="w-full py-4 bg-info font-semibold text-white text-lg rounded-xl hover:bg-blue-800 transition ease-in-out duration-500">Donate</button>
                    </div>

                    <div class="px-6 py-6 w-full border-2 border-gray-200 rounded-3xl">
                        <img src="images/donation-5.png" alt="Image" class="mb-6 hover:opacity-75 transition ease-in-out duration-500">
        
                        <h4 class="font-semibold text-gray-900 text-lg md:text-2xl mb-6">Lifeskills for children in <br> South Africa</h4>
        
                        <p class="font-light text-gray-400 text-sm md:text-md lg:text-lg mb-10">Donating for children in South Africa <br> by donating sincerely, regardless of...</p>
        
                        <div class="flex items-center justify-between mb-8">
                            <h6 class="font-light text-gray-400 text-sm md:text-lg">Goals : <span class="font-semibold text-gray-900 text-md md:text-lg">$45000</span></h6>
        
                            <h6 class="font-light text-gray-400 text-sm md:text-lg">Raised : <span class="font-semibold text-gray-900 text-md md:text-lg">$22500</span></h6>
                        </div>
        
                        <div class="hidden md:block lg:flex items-center justify-between mb-8">
                            <div>
                                <div class="w-72 h-2 bg-info opacity-10 rounded-lg absolute"></div>
        
                                <div class="w-36 h-2 bg-info rounded-lg relative"></div>
                            </div>
        
                            <p class="font-light text-gray-900 text-md">50%</p>
                        </div>

                        <button class="w-full py-4 bg-info font-semibold text-white text-lg rounded-xl hover:bg-blue-800 transition ease-in-out duration-500">Donate</button>
                    </div>

                    <div class="px-6 py-6 w-full border-2 border-gray-200 rounded-3xl">
                        <img src="images/donation-6.png" alt="Image" class="mb-6 hover:opacity-75 transition ease-in-out duration-500">
        
                        <h4 class="font-semibold text-gray-900 text-lg md:text-2xl mb-6">Help donate for children <br> with cancer</h4>
        
                        <p class="font-light text-gray-400 text-sm md:text-md lg:text-lg mb-10">Help donate for the children with <br> cancer by donating sincerely, regar...</p>
        
                        <div class="flex items-center justify-between mb-8">
                            <h6 class="font-light text-gray-400 text-sm md:text-lg">Goals : <span class="font-semibold text-gray-900 text-md md:text-lg">$50000</span></h6>
        
                            <h6 class="font-light text-gray-400 text-sm md:text-lg">Raised : <span class="font-semibold text-gray-900 text-md md:text-lg">$13000</span></h6>
                        </div>
        
                        <div class="hidden md:block lg:flex items-center justify-between mb-8">
                            <div>
                                <div class="w-72 h-2 bg-info opacity-10 rounded-lg absolute"></div>
        
                                <div class="w-20 h-2 bg-info rounded-lg relative"></div>
                            </div>
        
                            <p class="font-light text-gray-900 text-md">26%</p>
                        </div>

                        <button class="w-full py-4 bg-info font-semibold text-white text-lg rounded-xl hover:bg-blue-800 transition ease-in-out duration-500">Donate</button>
                    </div>
                </div> -->

                <!-- <div class="flex items-center justify-center">
                    <button class="px-7 py-5 font-semibold bg-gray-100 text-gray-900 rounded-2xl hover:bg-gray-300 hover:text-gray-600 transition ease-in-out duration-500">More to donate</button>
                </div> -->

            </div> <!-- container.// -->

        </section>
        <!-- donation section //end -->

        <!-- feature section -->
        <!-- <section class="bg-white py-16">

            <div class="container max-w-screen-xl mx-auto px-4">

                <div class="flex flex-col lg:flex-row justify-between space-x-16">
                    <div class="flex justify-center lg:justify-start">
                        <img src="images/feature-img.png" alt="Image">
                    </div>
        
                    <div class="mt-16">
                        <h1 class="font-semibold text-gray-900 text-xl md:text-4xl mb-20">You can help lots of people by <br> donating</h1>
        
                        <div class="grid grid-cols-1 md:grid-cols-2 md:space-x-20 mb-16">
                            <div class="mb-5 md:mb-0">
                                <div class="w-20 py-6 flex justify-center bg-info bg-opacity-5 rounded-xl mb-4">
                                    <i data-feather="sun" class="text-info"></i>
                                </div>
            
                                <h3 class="font-semibold text-gray-900 text-xl md:text-3xl mb-4">10000+</h3>
            
                                <p class="font-light text-gray-400 text-md md:text-lg">Fundraising campaign in <br> all time</p>
                            </div>
            
                            <div>
                                <div class="w-20 py-6 flex justify-center bg-red-500 bg-opacity-5 rounded-xl mb-4">
                                    <i data-feather="award" class="text-red-500"></i>
                                </div>
            
                                <h3 class="font-semibold text-gray-900 text-xl md:text-3xl mb-4">$120M+</h3>
            
                                <p class="font-light text-gray-400 text-md md:text-lg">Raised and counting <br> donations in all time</p>
                            </div>
                        </div>
        
                        <div class="grid grid-cols-1 md:grid-cols-2 md:space-x-20">
                            <div class="mb-5 md:mb-0">
                                <div class="w-20 py-6 flex justify-center bg-yellow-500 bg-opacity-5 rounded-xl mb-4">
                                    <i data-feather="users" class="text-yellow-500"></i>
                                </div>
            
                                <h3 class="font-semibold text-gray-900 text-xl md:text-3xl mb-4">1200+</h3>
            
                                <p class="font-light text-gray-400 text-md md:text-lg">Our volunteer around the <br> world</p>
                            </div>
            
                            <div>
                                <div class="w-20 py-6 flex justify-center bg-green-500 bg-opacity-5 rounded-xl mb-4">
                                    <i data-feather="trending-up" class="text-green-500"></i>
                                </div>
            
                                <h3 class="font-semibold text-gray-900 text-xl md:text-3xl mb-4">98%</h3>
            
                                <p class="font-light text-gray-400 text-md md:text-lg">Positive review from <br> public</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div> container.//

        </section>
        feature section //end -->

        <!-- join volunters section -->
        <!-- <section class="bg-white py-16">

            <div class="container max-w-screen-xl mx-auto px-4">

                <div class="w-full h-full bg-blue-500 rounded-2xl md:rounded-3xl relative lg:flex items-center">
                    <div class="hidden lg:block">
                        <img src="images/humans.png" alt="Image" class="relative z-10">

                        <img src="images/pattern-2.png" alt="Image" class="absolute top-14 left-40">

                        <img src="images/pattern.png" alt="Image" class="absolute top-0 z-0">
                    </div>

                    <div class="lg:relative py-4 lg:py-0">
                        <h1 class="font-semibold text-white text-xl md:text-4xl text-center lg:text-left leading-normal md:mb-5 lg:mb-10">Interested in volunteering? Join <br> with us now</h1>

                        <div class="hidden md:block flex items-center text-center lg:text-left space-x-5">
                            <input type="text" placeholder="Email" class="px-4 py-4 w-96 bg-gray-50 placeholder-gray-400 rounded-xl outline-none">

                            <button class="px-6 py-4 font-semibold bg-gray-50 text-info text-lg rounded-xl hover:bg-blue-500 hover:text-white transition ease-in-out duration-500">Join</button>
                        </div>
                    </div>
                </div>

            </div> container.//

        </section>
        join volunters section //end -->

        <footer class="bg-white py-16">

            <div class="container max-w-screen-xl mx-auto px-4">
                <div class="flex flex-col lg:flex-row lg:justify-between">

                    <div class="space-y-7 mb-10 lg:mb-0">
                        <div class="flex justify-center lg:justify-start">
                            <img src="images/book-4-fix.png" alt="Image">
                        </div>
                        
                        <p class="font-black text-gray-500 text-md md:text-xl mb-6 text-center lg:text-left">聯大二手書交易平台</p>

                        <div class="flex items-center justify-center lg:justify-start space-x-5">
                            <a href="#" class="px-3 py-3 bg-gray-200 text-gray-700 rounded-full hover:bg-info hover:text-white transition ease-in-out duration-500">
                                <i data-feather="facebook"></i>
                            </a>

                            <a href="#" class="px-3 py-3 bg-gray-200 text-gray-700 rounded-full hover:bg-info hover:text-white transition ease-in-out duration-500">
                                <i data-feather="twitter"></i>
                            </a>

                            <a href="#" class="px-3 py-3 bg-gray-200 text-gray-700 rounded-full hover:bg-info hover:text-white transition ease-in-out duration-500">
                                <i data-feather="linkedin"></i>
                            </a>
                        </div>
                    </div>

                    <div class="text-center lg:text-left space-y-7 mb-10 lg:mb-0">
                        <h4 class="font-semibold text-gray-900 text-lg md:text-2xl">STEP.1</h4>

                        <p class="block font-black text-gray-800 text-sm md:text-lg hover:text-gray-1000 transition ease-in-out duration-300">註冊帳戶</p>

                        <p class="block font-black text-gray-800 text-sm md:text-lg hover:text-gray-1000 transition ease-in-out duration-300">登入帳戶</p>

                        <p class="block font-black text-gray-800 text-sm md:text-lg hover:text-gray-1000 transition ease-in-out duration-300">開始使用</p>

                        <!-- <p class="block font-light text-gray-400 text-sm md:text-lg hover:text-gray-800 transition ease-in-out duration-300">About Us</p>   -->
                    </div>

                    <div class="text-center lg:text-left space-y-7 mb-10 lg:mb-0">
                        <h4 class="font-semibold text-gray-900 text-lg md:text-2xl">STEP.2</h4>

                        <p class="block font-black text-gray-800 text-sm md:text-lg hover:text-gray-1000 transition ease-in-out duration-300">點選菜單-商品</p>

                        <p class="block font-black text-gray-800 text-sm md:text-lg hover:text-gray-1000 transition ease-in-out duration-300">瀏覽商品</p>

                        <p class="block font-black text-gray-800 text-sm md:text-lg hover:text-gray-1000 transition ease-in-out duration-300">找到喜歡的書</p>

                        <!-- <p class="block font-black text-gray-500 text-sm md:text-lg hover:text-gray-800 transition ease-in-out duration-300">購買</p> -->
                    </div>

                    <div class="text-center lg:text-left space-y-7 mb-10 lg:mb-0">
                        <h4 class="font-semibold text-gray-900 text-lg md:text-2xl">STEP.3</h4>

                        <p class="block font-black text-gray-800 text-sm md:text-lg hover:text-gray-1000 transition ease-in-out duration-300">私訊賣家</p>

                        <p class="block font-black text-gray-800 text-sm md:text-lg hover:text-gray-1000 transition ease-in-out duration-300">完成交易</p>
                    </div>

                </div>
            </div> <!-- container.// -->

        </footer>

        <script>
            feather.replace()
        </script>

    </body>
</html>