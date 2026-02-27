<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyColoc - Gestion simplifiée des dépenses en colocation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary: #3B82F6;
            --secondary: #06B6D4;
            --accent: #10B981;
            --foreground: #1F2937;
            --background: #F9FAFB;
            --border: #E5E7EB;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: var(--foreground);
            background-color: var(--background);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
            padding: 12px 32px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            border: 2px solid var(--primary);
        }
        
        .btn-primary:hover {
            background-color: #2563EB;
            border-color: #2563EB;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }
        
        .btn-secondary {
            background-color: transparent;
            color: var(--primary);
            padding: 12px 32px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            border: 2px solid var(--primary);
        }
        
        .btn-secondary:hover {
            background-color: var(--primary);
            color: white;
        }
        
        .feature-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            color: white;
            font-size: 24px;
        }
        
        .step-number {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            font-weight: 700;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">EC</span>
                </div>
                <span class="font-bold text-xl text-gray-900">EasyColoc</span>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-gray-600 hover:text-gray-900 transition">Fonctionnalités</a>
                <a href="#how-it-works" class="text-gray-600 hover:text-gray-900 transition">Comment ça marche</a>
                <a href="#faq" class="text-gray-600 hover:text-gray-900 transition">FAQ</a>
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary text-sm">Tableau de bord</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 transition font-medium">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm">Inscription</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg py-24 md:py-32">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6">
                        <span class="gradient-text">Gérez vos dépenses</span> de colocation sans prise de tête
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Divisez les dépenses, suivez les dettes et simplifiez les remboursements entre colocataires. Transparent, équitable et instantané.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-primary">Accéder à mon compte</a>
                        @else
                            <a href="{{ route('register') }}" class="btn-primary">Commencer gratuitement</a>
                            <a href="{{ route('login') }}" class="btn-secondary">Se connecter</a>
                        @endauth
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-2xl blur-3xl opacity-20"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="font-medium text-gray-700">Alice doit à Bob</span>
                                <span class="text-lg font-bold text-blue-600">€25.50</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="font-medium text-gray-700">Charlie doit à Alice</span>
                                <span class="text-lg font-bold text-blue-600">€15.75</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border-2 border-green-200">
                                <span class="font-medium text-gray-700">David a payé</span>
                                <span class="text-lg font-bold text-green-600">✓</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-5xl font-bold text-blue-600 mb-2">0</div>
                    <p class="text-xl text-gray-600 font-medium">Calcul manuel requis</p>
                    <p class="text-gray-500 mt-2">Tout est automatisé pour vous</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-blue-600 mb-2">100%</div>
                    <p class="text-xl text-gray-600 font-medium">Transparent</p>
                    <p class="text-gray-500 mt-2">Chacun voit qui doit quoi</p>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-blue-600 mb-2">∞</div>
                    <p class="text-xl text-gray-600 font-medium">Colocataires</p>
                    <p class="text-gray-500 mt-2">Aucune limite de membres</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 md:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">Fonctionnalités clés</h2>
                <p class="text-xl text-gray-600">Tout ce dont vous avez besoin pour gérer votre colocation</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Feature 1 -->
                <div class="card-hover bg-white rounded-xl border border-gray-200 p-8">
                    <div class="feature-icon mb-6">💰</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Division intelligente</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Ajoutez une dépense, sélectionnez qui participe, et nous calculons automatiquement la part de chacun.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="card-hover bg-white rounded-xl border border-gray-200 p-8">
                    <div class="feature-icon mb-6">↔️</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Remboursements simplifiés</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Vue instantanée de qui doit quoi à qui. Plus de calculs compliqués, juste les vraies dettes.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="card-hover bg-white rounded-xl border border-gray-200 p-8">
                    <div class="feature-icon mb-6">👥</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Gestion des membres</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Invitez vos colocataires, suivez leur historique et gérez les arrivées/départs sans tracas.
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="card-hover bg-white rounded-xl border border-gray-200 p-8">
                    <div class="feature-icon mb-6">📊</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Historique complet</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Consultez toutes les dépenses, filtrez par période, et exportez vos données en un clic.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-24 md:py-32 gradient-bg">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">Comment ça marche</h2>
                <p class="text-xl text-gray-600">En 3 étapes simples pour démarrer</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="step-number">1</div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Créez une colocation</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Inscrivez-vous et créez votre première colocation en moins d'une minute.
                    </p>
                </div>
                
                <!-- Arrow -->
                <div class="hidden md:flex items-center justify-center">
                    <div class="text-4xl text-blue-600 font-light">→</div>
                </div>
                
                <!-- Step 2 -->
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="step-number">2</div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Invitez vos colos</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Partagez un lien d'invitation pour que vos colocataires rejoignent le groupe.
                    </p>
                </div>
                
                <!-- Arrow -->
                <div class="hidden md:flex items-center justify-center">
                    <div class="text-4xl text-blue-600 font-light">→</div>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center">
                    <div class="flex justify-center mb-6">
                        <div class="step-number">3</div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Suivez les dépenses</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Ajoutez les dépenses et laissez-nous calculer qui doit quoi automatiquement.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 md:py-32 bg-white">
        <div class="max-w-3xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">Questions fréquentes</h2>
            </div>
            
            <div class="space-y-6">
                <details class="border-2 border-gray-200 rounded-lg p-6 cursor-pointer hover:border-blue-300 transition">
                    <summary class="text-xl font-bold text-gray-900 flex items-center justify-between">
                        Combien coûte EasyColoc ?
                        <span class="text-2xl">+</span>
                    </summary>
                    <p class="text-gray-600 mt-4">EasyColoc est complètement gratuit. Pas de frais cachés, pas d'abonnement. Nous offrons la gestion des dépenses de colocation à tous.</p>
                </details>
                
                <details class="border-2 border-gray-200 rounded-lg p-6 cursor-pointer hover:border-blue-300 transition">
                    <summary class="text-xl font-bold text-gray-900 flex items-center justify-between">
                        Puis-je être dans plusieurs colocations ?
                        <span class="text-2xl">+</span>
                    </summary>
                    <p class="text-gray-600 mt-4">Non, pour des raisons de clarté, vous ne pouvez avoir qu'une seule colocation active à la fois. Cependant, vous pouvez la quitter pour en rejoindre une autre.</p>
                </details>
                
                <details class="border-2 border-gray-200 rounded-lg p-6 cursor-pointer hover:border-blue-300 transition">
                    <summary class="text-xl font-bold text-gray-900 flex items-center justify-between">
                        Mes données sont-elles sécurisées ?
                        <span class="text-2xl">+</span>
                    </summary>
                    <p class="text-gray-600 mt-4">Oui, nous utilisons le chiffrement HTTPS et les meilleures pratiques de sécurité pour protéger vos données. Seuls les membres de votre colocation peuvent voir vos dépenses.</p>
                </details>
                
                <details class="border-2 border-gray-200 rounded-lg p-6 cursor-pointer hover:border-blue-300 transition">
                    <summary class="text-xl font-bold text-gray-900 flex items-center justify-between">
                        Comment supprimer une dépense ?
                        <span class="text-2xl">+</span>
                    </summary>
                    <p class="text-gray-600 mt-4">Seul le propriétaire de la dépense peut la supprimer. Allez dans la section dépenses, trouvez la dépense, et cliquez sur supprimer.</p>
                </details>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 md:py-28 bg-gradient-to-r from-blue-600 to-cyan-600">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Prêt à simplifier votre colocation ?</h2>
            <p class="text-xl text-blue-100 mb-10">Rejoignez des centaines de colloques qui font confiance à EasyColoc</p>
            @auth
                <a href="{{ route('dashboard') }}" class="inline-block bg-white text-blue-600 font-bold py-4 px-10 rounded-lg hover:shadow-xl transition">
                    Accéder à mon tableau de bord
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-block bg-white text-blue-600 font-bold py-4 px-10 rounded-lg hover:shadow-xl transition">
                    Créer mon compte gratuitement
                </a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold">EC</span>
                        </div>
                        <span class="font-bold text-white">EasyColoc</span>
                    </div>
                    <p class="text-gray-400">Gestion de dépenses simplifiée pour colocations.</p>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-4">Produit</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition">Fonctionnalités</a></li>
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition">Guide</a></li>
                        <li><a href="#faq" class="text-gray-400 hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-4">Compte</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition">Connexion</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition">Inscription</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-4">Légal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Mentions légales</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Confidentialité</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Conditions</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center text-gray-500">
                <p>&copy; 2026 EasyColoc. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add active state to details elements
        document.querySelectorAll('details').forEach(detail => {
            detail.addEventListener('toggle', function() {
                if (this.open) {
                    this.querySelector('summary span').textContent = '−';
                } else {
                    this.querySelector('summary span').textContent = '+';
                }
            });
        });
    </script>
</body>
</html>
