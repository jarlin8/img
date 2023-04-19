<?php

const AIKIT_AUTO_GENERATOR_PROMPTS = [
    'en' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Generate a title for an article that discusses the following topic:\n[[description]]\nThe article will include the following sections:\n[[section-headlines]]\n\nTitle:",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Generate a title for an article that discusses the following topic:\n[[description]]\nThe article will include the following sections:\n[[section-headlines]]\nTry to use the following seo keywords when possible: [[keywords]]\n\nTitle:",
            ],
            'article-intro' => [
                'prompt' => "Write an introduction for an article that discusses the following topic:\n[[description]]\nThe article includes the following sections:\n[[section-headlines]]\n\nArticle Intro:",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Write an introduction for an article that discusses the following topic:\n[[description]]\nThe article includes the following sections:\n[[section-headlines]]\nTry to use the following seo keywords when possible: [[keywords]]\n\nArticle Intro:",
            ],
            'section-headlines' => [
                'prompt' => "Suggest a list of [[number-of-headlines]] possible headlines of sections for an article that will cover the following topic:\n[[description]]\n\nSection headlines:",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Suggest a list of [[number-of-headlines]] possible headlines of sections for an article that will cover the following topic:\n[[description]]\nTry to use the following seo keywords when possible: [[keywords]]\n\nSection headlines:",
            ],
            'section' => [
                'prompt' => "I'm writing an article about the follow topic:\n[[description]]\n\nAs part of this article, write a text section that discusses the following: [[section-headline]]\n\nSection body without the title:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "I'm writing an article about the follow topic:\n[[description]]\n\nAs part of this article, write a text section that discusses the following: [[section-headline]]\n\nTry to use the following seo keywords when possible: [[keywords]]\n\nSection body without the title:",
            ],
            'article-conclusion' => [
                'prompt' => "Write a conclusion for an article that discusses the following topic:\n[[description]]\nThe article includes the following sections:\n[[section-headlines]]\n\nArticle conclusion:",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Write a conclusion for an article that discusses the following topic:\n[[description]]\nThe article includes the following sections:\n[[section-headlines]]\nTry to use the following seo keywords when possible: [[keywords]]\n\nArticle conclusion:",
            ],
            'image' => [
                'prompt' => "Describe an image that would be best fit for this text:\n\n [[text]]\n\n---\nCreative image description in one sentence of 6 words:\n",
            ],
            'section-summary' => [
                'prompt' => "Write a short section summary for the following article section text:\n[[section]]\n\nSection summary:",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Write a short section summary for the following article section text:\n[[section]]\nTry to use the following seo keywords when possible: [[keywords]]\n\nSection summary:",
            ],
            'tldr' => [
                'prompt' => "Write a TL;DR for the following text:\n[[text]]\n\nTL;DR:",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Write a TL;DR for the following text:\n[[text]]\nTry to use the following seo keywords when possible: [[keywords]]\n\nTL;DR:",
            ],
        ]
    ],
    'lt' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Generuokite straipsnio pavadinimą, kuris aptariama šią temą:\n[[description]]\nStraipsnyje bus pateikiamos šios sekcijos:\n[[section-headlines]]\n\nPavadinimas:",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Generuokite straipsnio pavadinimą, kuris aptariama šią temą:\n[[description]]\nStraipsnyje bus pateikiamos šios sekcijos:\n[[section-headlines]]\nBandykite naudoti šiuos SEO raktažodžius, kai tai įmanoma: [[keywords]]\n\nPavadinimas:",
            ],
            'article-intro' => [
                'prompt' => "Parašykite įvadą straipsniui, kuris aptariama šią temą:\n[[description]]\nStraipsnyje pateikiamos šios sekcijos:\n[[section-headlines]]\n\nStraipsnio įvadas:",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Parašykite įvadą straipsniui, kuris aptariama šią temą:\n[[description]]\nStraipsnyje pateikiamos šios sekcijos:\n[[section-headlines]]\nBandykite naudoti šiuos SEO raktažodžius, kai tai įmanoma: [[keywords]]\n\nStraipsnio įvadas:",
            ],
            'section-headlines' => [
                'prompt' => "Pasiūlykite sąrašą iš [[number-of-headlines]] galimų skyriaus antraščių straipsniui, kuris aptariama šią temą:\n[[description]]\n\nSkyriaus antraštės:",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Pasiūlykite sąrašą iš [[number-of-headlines]] galimų skyriaus antraščių straipsniui, kuris aptariama šią temą:\n[[description]]\nBandykite naudoti šiuos SEO raktažodžius, kai tai įmanoma: [[keywords]]\n\nSkyriaus antraštės:",
            ],
            'section' => [
                'prompt' => "Rašau straipsnį apie šią temą:\n[[description]]\n\nKaip dalį šio straipsnio, parašykite teksto skyrių, kuris aptariama šią temą: [[section-headline]]\n\nSkyriaus tekstas be antraštės:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Rašau straipsnį apie šią temą:\n[[description]]\n\nKaip dalį šio straipsnio, parašykite teksto skyrių, kuris aptariama šią temą: [[section-headline]]\n\nBandykite naudoti šiuos SEO raktažodžius, kai tai įmanoma: [[keywords]]\n\nSkyriaus tekstas be antraštės:",
            ],
            'article-conclusion' => [
                'prompt' => "Parašykite išvadą straipsniui, kuris aptariama šią temą:\n[[description]]\nStraipsnyje pateikiamos šios sekcijos:\n[[section-headlines]]\n\nStraipsnio išvada:",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Parašykite išvadą straipsniui, kuris aptariama šią temą:\n[[description]]\nStraipsnyje pateikiamos šios sekcijos:\n[[section-headlines]]\nBandykite naudoti šiuos SEO raktažodžius, kai tai įmanoma: [[keywords]]\n\nStraipsnio išvada:",
            ],
            'image' => [
                'prompt' => "Aprašykite vaizdą, kuris geriausiai tiktų šiam tekstui:\n\n [[text]]\n\n---\nKūrybinio vaizdo aprašymas vienu sakiniu iš 6 žodžių:\n",
            ],
            'section-summary' => [
                'prompt' => "Parašykite trumpą skyriaus santrauką šiam straipsnio skyriaus tekste:\n[[section]]\n\nSkyriaus santrauka:",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Parašykite trumpą skyriaus santrauką šiam straipsnio skyriaus tekste:\n[[section]]\nBandykite naudoti šiuos SEO raktažodžius, kai tai įmanoma: [[keywords]]\n\nSkyriaus santrauka:",
            ],
            'tldr' => [
                'prompt' => "Parašykite TL;DR šiam teksto:\n[[text]]\n\nTL;DR:",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Parašykite TL;DR šiam teksto:\n[[text]]\nBandykite naudoti šiuos SEO raktažodžius, kai tai įmanoma: [[keywords]]\n\nTL;DR:",
            ],
        ],
    ],

    'de' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Generiere einen Titel für einen Artikel, der das folgende Thema behandelt:\n[[description]]\nDer Artikel wird folgende Abschnitte enthalten:\n[[section-headlines]]\n\nTitel:",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Generiere einen Titel für einen Artikel, der das folgende Thema behandelt:\n[[description]]\nDer Artikel wird folgende Abschnitte enthalten:\n[[section-headlines]]\nVersuche, die folgenden SEO-Schlüsselwörter zu verwenden, wenn möglich: [[keywords]]\n\nKreativer Titel:",
            ],
            'article-intro' => [
                'prompt' => "Schreibe eine Einleitung für einen Artikel, der das folgende Thema behandelt:\n[[description]]\nDer Artikel enthält folgende Abschnitte:\n[[section-headlines]]\n\nArtikel-Einleitung:",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Schreibe eine Einleitung für einen Artikel, der das folgende Thema behandelt:\n[[description]]\nDer Artikel enthält folgende Abschnitte:\n[[section-headlines]]\nVersuche, die folgenden SEO-Schlüsselwörter zu verwenden, wenn möglich: [[keywords]]\n\nArtikel-Einleitung:",
            ],
            'section-headlines' => [
                'prompt' => "Schlage eine Liste von [[number-of-headlines]] möglichen Überschriften von Abschnitten für einen Artikel vor, der das folgende Thema behandelt:\n[[description]]\n\nAbschnitts-Überschriften:",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Schlage eine Liste von [[number-of-headlines]] möglichen Überschriften von Abschnitten für einen Artikel vor, der das folgende Thema behandelt:\n[[description]]\nVersuche, die folgenden SEO-Schlüsselwörter zu verwenden, wenn möglich: [[keywords]]\n\nAbschnitts-Überschriften:",
            ],
            'section' => [
                'prompt' => "Ich schreibe einen Artikel über das folgende Thema:\n[[description]]\n\nAls Teil dieses Artikels schreibe einen Textabschnitt, der das folgende behandelt: [[section-headline]]\n\nAbschnitts-Body ohne den Titel:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Ich schreibe einen Artikel über das folgende Thema:\n[[description]]\n\nAls Teil dieses Artikels schreibe einen Textabschnitt, der das folgende behandelt: [[section-headline]]\n\nVersuche, die folgenden SEO-Schlüsselwörter zu verwenden, wenn möglich: [[keywords]]\n\nAbschnitts-Body ohne den Titel:",
            ],
            'article-conclusion' => [
                'prompt' => "Schreibe eine Schlussfolgerung für einen Artikel, der das folgende Thema behandelt:\n[[description]]\nDer Artikel enthält folgende Abschnitte:\n[[section-headlines]]\n\nArtikel-Schlussfolgerung:",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Schreibe eine Schlussfolgerung für einen Artikel, der das folgende Thema behandelt:\n[[description]]\nDer Artikel enthält folgende Abschnitte:\n[[section-headlines]]\nVersuche, die folgenden SEO-Schlüsselwörter zu verwenden, wenn möglich: [[keywords]]\n\nArtikel-Schlussfolgerung:",
            ],
            'image' => [
                'prompt' => "Beschreibe ein Bild, das am besten zu diesem Text passt:\n\n [[text]]\n\n---\nKreative Bildbeschreibung in einem Satz von 6 Wörtern:\n",
            ],
            'section-summary' => [
                'prompt' => "Schreibe eine kurze Abschnittszusammenfassung für den folgenden Artikel-Abschnittstext:\n[[section]]\n\nAbschnittszusammenfassung:",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Schreibe eine kurze Abschnittszusammenfassung für den folgenden Artikel-Abschnittstext:\n[[section]]\nVersuche, die folgenden SEO-Schlüsselwörter zu verwenden, wenn möglich: [[keywords]]\n\nAbschnittszusammenfassung:",
            ],
            'tldr' => [
                'prompt' => "Schreibe ein TL;DR für den folgenden Text:\n[[text]]\n\nTL;DR:",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Schreibe ein TL;DR für den folgenden Text:\n[[text]]\nVersuche, die folgenden SEO-Schlüsselwörter zu verwenden, wenn möglich: [[keywords]]\n\nTL;DR:",
            ],
        ],
    ],
    'fr' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Générer un titre pour un article qui traite du sujet suivant:\n[[description]]\nL'article contiendra les sections suivantes:\n[[section-headlines]]\n\nTitre:",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Générer un titre pour un article qui traite du sujet suivant:\n[[description]]\nL'article contiendra les sections suivantes:\n[[section-headlines]]\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]\n\nTitre:",
            ],
            'article-intro' => [
                'prompt' => "Écrire une introduction pour un article qui traite du sujet suivant:\n[[description]]\nL'article contient les sections suivantes:\n[[section-headlines]]\n\nIntroduction:",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Écrire une introduction pour un article qui traite du sujet suivant:\n[[description]]\nL'article contient les sections suivantes:\n[[section-headlines]]\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]\n\nIntroduction:",
            ],
            'section-headlines' => [
                'prompt' => "Proposer une liste de [[number-of-headlines]] titres de section possibles pour un article qui traite du sujet suivant:\n[[description]]\n\nTitres de section:",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Proposer une liste de [[number-of-headlines]] titres de section possibles pour un article qui traite du sujet suivant:\n[[description]]\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]\n\nTitres de section:",
            ],
            'section' => [
                'prompt' => "J'écris un article sur le sujet suivant:\n[[description]]\n\nEn tant que partie de cet article, écrivez un paragraphe qui traite de: [[section-headline]]\n\nCorps de la section sans le titre:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "J'écris un article sur le sujet suivant:\n[[description]]\n\nEn tant que partie de cet article, écrivez un paragraphe qui traite de: [[section-headline]]\n\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]\n\nCorps de la section sans le titre:",
            ],
            'article-conclusion' => [
                'prompt' => "Écrire une conclusion pour un article qui traite du sujet suivant:\n[[description]]\nL'article contient les sections suivantes:\n[[section-headlines]]\n\nConclusion de l'article:",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Écrire une conclusion pour un article qui traite du sujet suivant:\n[[description]]\nL'article contient les sections suivantes:\n[[section-headlines]]\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]\n\nConclusion de l'article:",
            ],
            'image' => [
                'prompt' => "Décrire une image qui correspond le mieux à ce texte:\n\n [[text]]\n\n---\nDescription créative de l'image en une phrase de 6 mots:\n",
            ],
            'section-summary' => [
                'prompt' => "Écrire un résumé de section pour le texte de section d'article suivant:\n[[section]]\n\nRésumé de section:",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Écrire un résumé de section pour le texte de section d'article suivant:\n[[section]]\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]\n\nRésumé de section:",
            ],
            'tldr' => [
                'prompt' => "Écrire un TL;DR pour le texte suivant:\n[[text]]\n\nTL;DR:",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Écrire un TL;DR pour le texte suivant:\n[[text]]\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]\n\nTL;DR:",
            ],
        ],
    ],
    'es' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Generar un título para un artículo que trate del siguiente tema:\n[[description]]\nEl artículo contendrá las siguientes secciones:\n[[section-headlines]]\n\nTítulo:",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Generar un título para un artículo que trate del siguiente tema:\n[[description]]\nEl artículo contendrá las siguientes secciones:\n[[section-headlines]]\nIntenta usar las siguientes palabras clave de SEO si es posible: [[keywords]]\n\nTítulo:",
            ],
            'article-intro' => [
                'prompt' => "Escribir una introducción para un artículo que trate del siguiente tema:\n[[description]]\nEl artículo contiene las siguientes secciones:\n[[section-headlines]]\n\nIntroducción:",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Escribir una introducción para un artículo que trate del siguiente tema:\n[[description]]\nEl artículo contiene las siguientes secciones:\n[[section-headlines]]\nIntenta usar las siguientes palabras clave de SEO si es posible: [[keywords]]\n\nIntroducción:",
            ],
            'section-headlines' => [
                'prompt' => "Proporcionar una lista de [[number-of-headlines]] posibles títulos de sección para un artículo que trate del siguiente tema:\n[[description]]\n\nTítulos de sección:",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Proporcionar una lista de [[number-of-headlines]] posibles títulos de sección para un artículo que trate del siguiente tema:\n[[description]]\nIntenta usar las siguientes palabras clave de SEO si es posible: [[keywords]]\n\nTítulos de sección:",
            ],
            'section' => [
                'prompt' => "Estoy escribiendo un artículo sobre el siguiente tema:\n[[description]]\n\nComo parte de este artículo, escribe un párrafo que trate de: [[section-headline]]\n\nCuerpo de la sección sin el título:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Estoy escribiendo un artículo sobre el siguiente tema:\n[[description]]\n\nComo parte de este artículo, escribe un párrafo que trate de: [[section-headline]]\n\nIntenta usar las siguientes palabras clave de SEO si es posible: [[keywords]]\n\nCuerpo de la sección sin el título:",
            ],
            'article-conclusion' => [
                'prompt' => "Escribir una conclusión para un artículo que trate del siguiente tema:\n[[description]]\nEl artículo contiene las siguientes secciones:\n[[section-headlines]]\n\nConclusión del artículo:",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Escribir una conclusión para un artículo que trate del siguiente tema:\n[[description]]\nEl artículo contiene las siguientes secciones:\n[[section-headlines]]\nIntenta usar las siguientes palabras clave de SEO si es posible: [[keywords]]\n\nConclusión del artículo:",
            ],
            'image' => [
                'prompt' => "Describir una imagen que corresponda mejor al siguiente texto:\n\n [[text]]\n\n---\nDescripción creativa de la imagen en una frase de 6 palabras:\n",
            ],
            'section-summary' => [
                'prompt' => "Escribir un resumen de sección para el siguiente texto de sección de artículo:\n[[section]]\n\nResumen de sección:",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Escribir un resumen de sección para el siguiente texto de sección de artículo:\n[[section]]\nIntenta usar las siguientes palabras clave de SEO si es posible: [[keywords]]\n\nResumen de sección:",
            ],
            'tldr' => [
                'prompt' => "Escribir un TL;DR para el siguiente texto:\n[[text]]\n\nTL;DR:",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Escribir un TL;DR para el siguiente texto:\n[[text]]\nIntenta usar las siguientes palabras clave de SEO si es posible: [[keywords]]\n\nTL;DR:",
            ],
        ],
    ],
    'it' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Generare un titolo per un articolo che tratta del seguente argomento:\n[[description]]\nL'articolo conterrà le seguenti sezioni:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Generare un titolo per un articolo che tratta del seguente argomento:\n[[description]]\nL'articolo conterrà le seguenti sezioni:\n[[section-headlines]]\nProva a usare le seguenti parole chiave SEO se possibile: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Scrivere un'introduzione per un articolo che tratta del seguente argomento:\n[[description]]\nL'articolo contiene le seguenti sezioni:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Scrivere un'introduzione per un articolo che tratta del seguente argomento:\n[[description]]\nL'articolo contiene le seguenti sezioni:\n[[section-headlines]]\nProva a usare le seguenti parole chiave SEO se possibile: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Fornire un elenco di [[number-of-headlines]] possibili titoli di sezione per un articolo che tratta del seguente argomento:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Fornire un elenco di [[number-of-headlines]] possibili titoli di sezione per un articolo che tratta del seguente argomento:\n[[description]]\nProva a usare le seguenti parole chiave SEO se possibile: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Sto scrivendo un articolo sul seguente argomento:\n[[description]]\n\nCome parte di questo articolo, scrivi un paragrafo che tratta di: [[section-headline]]\n\nCorpo della sezione senza il titolo:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Sto scrivendo un articolo sul seguente argomento:\n[[description]]\n\nCome parte di questo articolo, scrivi un paragrafo che tratta di: [[section-headline]]\n\nProva a usare le seguenti parole chiave SEO se possibile: [[keywords]]\n\nCorpo della sezione senza il titolo:",
            ],
            'article-conclusion' => [
                'prompt' => "Scrivere una conclusione per un articolo che tratta del seguente argomento:\n[[description]]\nL'articolo contiene le seguenti sezioni:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Scrivere una conclusione per un articolo che tratta del seguente argomento:\n[[description]]\nL'articolo contiene le seguenti sezioni:\n[[section-headlines]]\nProva a usare le seguenti parole chiave SEO se possibile: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Descrivere un'immagine che corrisponda meglio al seguente testo:\n\n [[text]]\n\n---\nDescrizione creativa dell'immagine in una frase di 6 parole:\n",
            ],
            'section-summary' => [
                'prompt' => "Scrivere un riassunto di sezione per il seguente testo di sezione di articolo:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Scrivere un riassunto di sezione per il seguente testo di sezione di articolo:\n[[section]]\nProva a usare le seguenti parole chiave SEO se possibile: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Scrivere un TL;DR per il seguente testo:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Scrivere un TL;DR per il seguente testo:\n[[text]]\nProva a usare le seguenti parole chiave SEO se possibile: [[keywords]]",
            ],
        ],
    ],
    'pt' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Gerar um título para um artigo que trata do seguinte assunto:\n[[description]]\nO artigo terá as seguintes seções:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Gerar um título para um artigo que trata do seguinte assunto:\n[[description]]\nO artigo terá as seguintes seções:\n[[section-headlines]]\nTente usar as seguintes palavras-chave de SEO, se possível: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Escrever uma introdução para um artigo que trata do seguinte assunto:\n[[description]]\nO artigo contém as seguintes seções:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Escrever uma introdução para um artigo que trata do seguinte assunto:\n[[description]]\nO artigo contém as seguintes seções:\n[[section-headlines]]\nTente usar as seguintes palavras-chave de SEO, se possível: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Fornecer uma lista de [[number-of-headlines]] possíveis títulos de seção para um artigo que trata do seguinte assunto:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Fornecer uma lista de [[number-of-headlines]] possíveis títulos de seção para um artigo que trata do seguinte assunto:\n[[description]]\nTente usar as seguintes palavras-chave de SEO, se possível: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Estou escrevendo um artigo sobre o seguinte assunto:\n[[description]]\n\nComo parte deste artigo, escreva um parágrafo que trata de: [[section-headline]]\n\nCorpo da seção sem o título:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Estou escrevendo um artigo sobre o seguinte assunto:\n[[description]]\n\nComo parte deste artigo, escreva um parágrafo que trata de: [[section-headline]]\n\nTente usar as seguintes palavras-chave de SEO, se possível: [[keywords]]\n\nCorpo da seção sem o título:",
            ],
            'article-conclusion' => [
                'prompt' => "Escrever uma conclusão para um artigo que trata do seguinte assunto:\n[[description]]\nO artigo contém as seguintes seções:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Escrever uma conclusão para um artigo que trata do seguinte assunto:\n[[description]]\nO artigo contém as seguintes seções:\n[[section-headlines]]\nTente usar as seguintes palavras-chave de SEO, se possível: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Descrever uma imagem que corresponda melhor ao seguinte texto:\n\n [[text]]\n\n---\nDescrição criativa da imagem em uma frase de 6 palavras:\n",
            ],
            'section-summary' => [
                'prompt' => "Escrever um resumo de seção para o seguinte texto de seção de artigo:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Escrever um resumo de seção para o seguinte texto de seção de artigo:\n[[section]]\nTente usar as seguintes palavras-chave de SEO, se possível: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Escrever um TL;DR para o seguinte texto:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Escrever um TL;DR para o seguinte texto:\n[[text]]\nTente usar as seguintes palavras-chave de SEO, se possível: [[keywords]]",
            ],
        ],
    ],
    'nl' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Genereer een titel voor een artikel over het volgende onderwerp:\n[[description]]\nHet artikel zal de volgende secties bevatten:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Genereer een titel voor een artikel over het volgende onderwerp:\n[[description]]\nHet artikel zal de volgende secties bevatten:\n[[section-headlines]]\nProbeer de volgende SEO sleutelwoorden te gebruiken, indien mogelijk: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Schrijf een inleiding voor een artikel over het volgende onderwerp:\n[[description]]\nHet artikel bevat de volgende secties:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Schrijf een inleiding voor een artikel over het volgende onderwerp:\n[[description]]\nHet artikel bevat de volgende secties:\n[[section-headlines]]\nProbeer de volgende SEO sleutelwoorden te gebruiken, indien mogelijk: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Geef een lijst van [[number-of-headlines]] mogelijke sectietitels voor een artikel over het volgende onderwerp:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Geef een lijst van [[number-of-headlines]] mogelijke sectietitels voor een artikel over het volgende onderwerp:\n[[description]]\nProbeer de volgende SEO sleutelwoorden te gebruiken, indien mogelijk: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Ik ben een artikel aan het schrijven over het volgende onderwerp:\n[[description]]\n\nAls onderdeel van dit artikel, schrijf een paragraaf over: [[section-headline]]\n\nSectie-tekst zonder de titel:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Ik ben een artikel aan het schrijven over het volgende onderwerp:\n[[description]]\n\nAls onderdeel van dit artikel, schrijf een paragraaf over: [[section-headline]]\n\nProbeer de volgende SEO sleutelwoorden te gebruiken, indien mogelijk: [[keywords]]\n\nSectie-tekst zonder de titel:",
            ],
            'article-conclusion' => [
                'prompt' => "Schrijf een conclusie voor een artikel over het volgende onderwerp:\n[[description]]\nHet artikel bevat de volgende secties:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Schrijf een conclusie voor een artikel over het volgende onderwerp:\n[[description]]\nHet artikel bevat de volgende secties:\n[[section-headlines]]\nProbeer de volgende SEO sleutelwoorden te gebruiken, indien mogelijk: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Beschrijf een afbeelding die het beste past bij de volgende tekst:\n\n [[text]]\n\n---\nCreatieve beschrijving van de afbeelding in een zin van 6 woorden:\n",
            ],
            'section-summary' => [
                'prompt' => "Schrijf een samenvatting van een sectie voor de volgende tekst van een artikelsectie:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Schrijf een samenvatting van een sectie voor de volgende tekst van een artikelsectie:\n[[section]]\nProbeer de volgende SEO sleutelwoorden te gebruiken, indien mogelijk: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Schrijf een TL;DR voor de volgende tekst:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Schrijf een TL;DR voor de volgende tekst:\n[[text]]\nProbeer de volgende SEO sleutelwoorden te gebruiken, indien mogelijk: [[keywords]]",
            ],
        ],
    ],
    'pl' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Wygeneruj tytuł artykułu na temat:\n[[description]]\nArtykuł będzie zawierał następujące sekcje:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Wygeneruj tytuł artykułu na temat:\n[[description]]\nArtykuł będzie zawierał następujące sekcje:\n[[section-headlines]]\nSpróbuj użyć następujących słów kluczowych SEO, jeśli to możliwe: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Napisz wprowadzenie do artykułu na temat:\n[[description]]\nArtykuł zawiera następujące sekcje:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Napisz wprowadzenie do artykułu na temat:\n[[description]]\nArtykuł zawiera następujące sekcje:\n[[section-headlines]]\nSpróbuj użyć następujących słów kluczowych SEO, jeśli to możliwe: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Podaj listę [[number-of-headlines]] możliwych tytułów sekcji dla artykułu na temat:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Podaj listę [[number-of-headlines]] możliwych tytułów sekcji dla artykułu na temat:\n[[description]]\nSpróbuj użyć następujących słów kluczowych SEO, jeśli to możliwe: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Piszę artykuł na temat:\n[[description]]\n\nJako część tego artykułu, napisz akapit na temat: [[section-headline]]\n\nTekst sekcji bez tytułu:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Piszę artykuł na temat:\n[[description]]\n\nJako część tego artykułu, napisz akapit na temat: [[section-headline]]\n\nSpróbuj użyć następujących słów kluczowych SEO, jeśli to możliwe: [[keywords]]\n\nTekst sekcji bez tytułu:",
            ],
            'article-conclusion' => [
                'prompt' => "Napisz podsumowanie artykułu na temat:\n[[description]]\nArtykuł zawiera następujące sekcje:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Napisz podsumowanie artykułu na temat:\n[[description]]\nArtykuł zawiera następujące sekcje:\n[[section-headlines]]\nSpróbuj użyć następujących słów kluczowych SEO, jeśli to możliwe: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Opisz obraz, który najlepiej pasuje do następującego tekstu:\n\n [[text]]\n\n---\nKreatywny opis obrazu w jednym zdaniu z 6 słowami:\n",
            ],
            'section-summary' => [
                'prompt' => "Napisz podsumowanie sekcji dla następującego tekstu sekcji artykułu:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Napisz podsumowanie sekcji dla następującego tekstu sekcji artykułu:\n[[section]]\nSpróbuj użyć następujących słów kluczowych SEO, jeśli to możliwe: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Napisz TL;DR dla następującego tekstu:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Napisz TL;DR dla następującego tekstu:\n[[text]]\nSpróbuj użyć następujących słów kluczowych SEO, jeśli to możliwe: [[keywords]]",
            ],
        ],
    ],
    'ru' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Сгенерировать заголовок статьи на тему:\n[[description]]\nСтатья будет содержать следующие разделы:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Сгенерировать заголовок статьи на тему:\n[[description]]\nСтатья будет содержать следующие разделы:\n[[section-headlines]]\nПопробуйте использовать следующие ключевые слова SEO, если это возможно: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Напишите вступление к статье на тему:\n[[description]]\nСтатья содержит следующие разделы:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Напишите вступление к статье на тему:\n[[description]]\nСтатья содержит следующие разделы:\n[[section-headlines]]\nПопробуйте использовать следующие ключевые слова SEO, если это возможно: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Укажите список [[number-of-headlines]] возможных заголовков разделов для статьи на тему:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Укажите список [[number-of-headlines]] возможных заголовков разделов для статьи на тему:\n[[description]]\nПопробуйте использовать следующие ключевые слова SEO, если это возможно: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Я пишу статью на тему:\n[[description]]\n\nКак часть этой статьи, напишите абзац на тему: [[section-headline]]\n\nТекст раздела без заголовка:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Я пишу статью на тему:\n[[description]]\n\nКак часть этой статьи, напишите абзац на тему: [[section-headline]]\n\nПопробуйте использовать следующие ключевые слова SEO, если это возможно: [[keywords]]\n\nТекст раздела без заголовка:",
            ],
            'article-conclusion' => [
                'prompt' => "Напишите заключение статьи на тему:\n[[description]]\nСтатья содержит следующие разделы:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Напишите заключение статьи на тему:\n[[description]]\nСтатья содержит следующие разделы:\n[[section-headlines]]\nПопробуйте использовать следующие ключевые слова SEO, если это возможно: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Опишите изображение, которое лучше всего подходит для следующего текста:\n\n [[text]]\n\n---\nТворческое описание изображения в одном предложении с 6 словами:\n",
            ],
            'section-summary' => [
                'prompt' => "Напишите резюме раздела для следующего текста раздела статьи:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Напишите резюме раздела для следующего текста раздела статьи:\n[[section]]\nПопробуйте использовать следующие ключевые слова SEO, если это возможно: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Напишите TL;DR для следующего текста:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Напишите TL;DR для следующего текста:\n[[text]]\nПопробуйте использовать следующие ключевые слова SEO, если это возможно: [[keywords]]",
            ],
        ],
    ],
    'ja' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "次のテーマに関する記事のタイトルを生成します:\n[[description]]\n記事には次のセクションが含まれます:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "次のテーマに関する記事のタイトルを生成します:\n[[description]]\n記事には次のセクションが含まれます:\n[[section-headlines]]\nSEOキーワードを使用できる場合は、次のキーワードを使用してください: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "次のテーマに関する記事の導入を書きます:\n[[description]]\n記事には次のセクションが含まれます:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "次のテーマに関する記事の導入を書きます:\n[[description]]\n記事には次のセクションが含まれます:\n[[section-headlines]]\nSEOキーワードを使用できる場合は、次のキーワードを使用してください: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "次のテーマに関する記事の可能なセクションタイトルのリストを指定してください:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "次のテーマに関する記事の可能なセクションタイトルのリストを指定してください:\n[[description]]\nSEOキーワードを使用できる場合は、次のキーワードを使用してください: [[keywords]]",
            ],
            'section' => [
                'prompt' => "次のテーマに関する記事を書いています:\n[[description]]\n\nこの記事の一部として、次のセクションのテーマに関する段落を書いてください: [[section-headline]]\n\nセクションのテキスト:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "次のテーマに関する記事を書いています:\n[[description]]\n\nこの記事の一部として、次のセクションのテーマに関する段落を書いてください: [[section-headline]]\n\nSEOキーワードを使用できる場合は、次のキーワードを使用してください: [[keywords]]\n\nセクションのテキスト:",
            ],
            'article-conclusion' => [
                'prompt' => "次のテーマに関する結論を書きます:\n[[description]]\n記事には次のセクションが含まれます:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "次のテーマに関する結論を書きます:\n[[description]]\n記事には次のセクションが含まれます:\n[[section-headlines]]\nSEOキーワードを使用できる場合は、次のキーワードを使用してください: [[keywords]]",
            ],
            'image' => [
                'prompt' => "次のテキストに最も適した画像を説明してください:\n\n [[text]]\n\n---\n画像のクリエイティブな説明は、1つの文で6つの単語を含みます:\n",
            ],
            'section-summary' => [
                'prompt' => "次のセクションテキストの要約を書いてください:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "次のセクションテキストの要約を書いてください:\n[[section]]\nSEOキーワードを使用できる場合は、次のキーワードを使用してください: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "次のテキストのTL;DRを書いてください:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "次のテキストのTL;DRを書いてください:\n[[text]]\nSEOキーワードを使用できる場合は、次のキーワードを使用してください: [[keywords]]",
            ],
        ],
    ],
    'zh' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "生成有关以下主题的文章标题:\n[[description]]\n文章包含以下部分:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "生成有关以下主题的文章标题:\n[[description]]\n文章包含以下部分:\n[[section-headlines]]\n如果可以，请尝试使用以下SEO关键字: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "写一篇有关以下主题的文章介绍:\n[[description]]\n文章包含以下部分:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "写一篇有关以下主题的文章介绍:\n[[description]]\n文章包含以下部分:\n[[section-headlines]]\n如果可以，请尝试使用以下SEO关键字: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "请指定有关以下主题的文章可能的部分标题列表:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "请指定有关以下主题的文章可能的部分标题列表:\n[[description]]\n如果可以，请尝试使用以下SEO关键字: [[keywords]]",
            ],
            'section' => [
                'prompt' => "正在写一篇有关以下主题的文章:\n[[description]]\n\n作为文章的一部分，请写一段有关以下部分主题的段落: [[section-headline]]\n\n部分文本:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "正在写一篇有关以下主题的文章:\n[[description]]\n\n作为文章的一部分，请写一段有关以下部分主题的段落: [[section-headline]]\n\n如果可以，请尝试使用以下SEO关键字: [[keywords]]\n\n部分文本:",
            ],
            'article-conclusion' => [
                'prompt' => "写一篇有关以下主题的文章结论:\n[[description]]\n文章包含以下部分:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "写一篇有关以下主题的文章结论:\n[[description]]\n文章包含以下部分:\n[[section-headlines]]\n如果可以，请尝试使用以下SEO关键字: [[keywords]]",
            ],
            'image' => [
                'prompt' => "请描述最适合以下文本的图片:\n\n [[text]]\n\n---\n图片的创意描述应包含6个单词的1个句子:\n",
            ],
            'section-summary' => [
                'prompt' => "请写一段有关以下部分文本的摘要:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "请写一段有关以下部分文本的摘要:\n[[section]]\n如果可以，请尝试使用以下SEO关键字: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "请写一段有关以下文本的TL;DR:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "请写一段有关以下文本的TL;DR:\n[[text]]\n如果可以，请尝试使用以下SEO关键字: [[keywords]]",
            ],
        ],
    ],
    'br' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Générez un titre d'article sur le sujet suivant:\n[[description]]\nL'article contient les sections suivantes:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Générez un titre d'article sur le sujet suivant:\n[[description]]\nL'article contient les sections suivantes:\n[[section-headlines]]\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Écrivez une introduction d'article sur le sujet suivant:\n[[description]]\nL'article contient les sections suivantes:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Écrivez une introduction d'article sur le sujet suivant:\n[[description]]\nL'article contient les sections suivantes:\n[[section-headlines]]\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Veuillez spécifier une liste possible de titres de section pour l'article sur le sujet suivant:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Veuillez spécifier une liste possible de titres de section pour l'article sur le sujet suivant:\n[[description]]\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Vous écrivez un article sur le sujet suivant:\n[[description]]\n\nEn tant que partie de l'article, veuillez écrire un paragraphe sur le sujet suivant: [[section-headline]]\n\nTexte de la section:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Vous écrivez un article sur le sujet suivant:\n[[description]]\n\nEn tant que partie de l'article, veuillez écrire un paragraphe sur le sujet suivant: [[section-headline]]\n\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]\n\nTexte de la section:",
            ],
            'article-conclusion' => [
                'prompt' => "Écrivez une conclusion d'article sur le sujet suivant:\n[[description]]\nL'article contient les sections suivantes:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Écrivez une conclusion d'article sur le sujet suivant:\n[[description]]\nL'article contient les sections suivantes:\n[[section-headlines]]\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Veuillez décrire l'image la plus appropriée pour le texte suivant:\n\n [[text]]\n\n---\nLa description créative de l'image doit contenir une phrase de 6 mots:\n",
            ],
            'section-summary' => [
                'prompt' => "Veuillez écrire un résumé de la section suivante:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Veuillez écrire un résumé de la section suivante:\n[[section]]\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Veuillez écrire un TL;DR pour le texte suivant:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Veuillez écrire un TL;DR pour le texte suivant:\n[[text]]\nEssayez d'utiliser les mots-clés SEO suivants si possible: [[keywords]]",
            ],
        ],
    ],
    'tr' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Aşağıdaki konuyla ilgili bir makale başlığı oluşturun:\n[[description]]\nMakale şu bölümleri içerir:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Aşağıdaki konuyla ilgili bir makale başlığı oluşturun:\n[[description]]\nMakale şu bölümleri içerir:\n[[section-headlines]]\nMümkünse, aşağıdaki SEO anahtar kelimelerini kullanmaya çalışın: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Aşağıdaki konuyla ilgili bir makale girişi yazın:\n[[description]]\nMakale şu bölümleri içerir:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Aşağıdaki konuyla ilgili bir makale girişi yazın:\n[[description]]\nMakale şu bölümleri içerir:\n[[section-headlines]]\nMümkünse, aşağıdaki SEO anahtar kelimelerini kullanmaya çalışın: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Lütfen aşağıdaki konuyla ilgili makale için olası bir bölüm başlığı listesi belirtin:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Lütfen aşağıdaki konuyla ilgili makale için olası bir bölüm başlığı listesi belirtin:\n[[description]]\nMümkünse, aşağıdaki SEO anahtar kelimelerini kullanmaya çalışın: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Aşağıdaki konuyla ilgili bir makale yazıyorsunuz:\n[[description]]\n\nMakalenin bir parçası olarak, lütfen aşağıdaki konuyla ilgili bir paragraf yazın: [[section-headline]]\n\nBölüm metni:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Aşağıdaki konuyla ilgili bir makale yazıyorsunuz:\n[[description]]\n\nMakalenin bir parçası olarak, lütfen aşağıdaki konuyla ilgili bir paragraf yazın: [[section-headline]]\n\nMümkünse, aşağıdaki SEO anahtar kelimelerini kullanmaya çalışın: [[keywords]]\n\nBölüm metni:",
            ],
            'article-conclusion' => [
                'prompt' => "Aşağıdaki konuyla ilgili bir makale sonucu yazın:\n[[description]]\nMakale şu bölümleri içerir:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Aşağıdaki konuyla ilgili bir makale sonucu yazın:\n[[description]]\nMakale şu bölümleri içerir:\n[[section-headlines]]\nMümkünse, aşağıdaki SEO anahtar kelimelerini kullanmaya çalışın: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Aşağıdaki metin için en uygun görüntüyü açıklayın:\n\n [[text]]\n\n---\nGörüntü yaratıcı açıklaması 6 kelime içeren bir cümle olmalıdır:\n",
            ],
            'section-summary' => [
                'prompt' => "Lütfen aşağıdaki bölümün özeti yazın:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Lütfen aşağıdaki bölümün özeti yazın:\n[[section]]\nMümkünse, aşağıdaki SEO anahtar kelimelerini kullanmaya çalışın: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Aşağıdaki metin için bir TL; DR yazın:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Aşağıdaki metin için bir TL; DR yazın:\n[[text]]\nMümkünse, aşağıdaki SEO anahtar kelimelerini kullanmaya çalışın: [[keywords]]",
            ],
        ],
    ],
    'ar' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "أنشئ عنوان مقالة للموضوع التالي:\n[[description]]\nالمقالة تحتوي على الأقسام التالية:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "أنشئ عنوان مقالة للموضوع التالي:\n[[description]]\nالمقالة تحتوي على الأقسام التالية:\n[[section-headlines]]\nحاول استخدام الكلمات الرئيسية التالية إذا كان ذلك ممكناً: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "أكتب مقدمة مقالة للموضوع التالي:\n[[description]]\nالمقالة تحتوي على الأقسام التالية:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "أكتب مقدمة مقالة للموضوع التالي:\n[[description]]\nالمقالة تحتوي على الأقسام التالية:\n[[section-headlines]]\nحاول استخدام الكلمات الرئيسية التالية إذا كان ذلك ممكناً: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "يرجى تحديد قائمة بعناوين الأقسام المحتملة للموضوع التالي:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "يرجى تحديد قائمة بعناوين الأقسام المحتملة للموضوع التالي:\n[[description]]\nحاول استخدام الكلمات الرئيسية التالية إذا كان ذلك ممكناً: [[keywords]]",
            ],
            'section' => [
                'prompt' => "أنت تكتب مقالة حول الموضوع التالي:\n[[description]]\n\nكجزء من المقالة، يرجى كتابة الفقرة التالية:\n[[section-headline]]\n\nنص الفقرة:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "أنت تكتب مقالة حول الموضوع التالي:\n[[description]]\n\nكجزء من المقالة، يرجى كتابة الفقرة التالية:\n[[section-headline]]\n\nحاول استخدام الكلمات الرئيسية التالية إذا كان ذلك ممكناً: [[keywords]]\n\nنص الفقرة:",
            ],
            'article-conclusion' => [
                'prompt' => "أكتب نهاية مقالة للموضوع التالي:\n[[description]]\nالمقالة تحتوي على الأقسام التالية:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "أكتب نهاية مقالة للموضوع التالي:\n[[description]]\nالمقالة تحتوي على الأقسام التالية:\n[[section-headlines]]\nحاول استخدام الكلمات الرئيسية التالية إذا كان ذلك ممكناً: [[keywords]]",
            ],
            'image' => [
                'prompt' => "يرجى تحديد الصورة الأكثر تناسباً للنص التالي:\n\n [[text]]\n\n---\nوصف الصورة يجب أن يكون جملة تحتوي على 6 كلمات:\n",
            ],
            'section-summary' => [
                'prompt' => "يرجى كتابة ملخص للفقرة التالية:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "يرجى كتابة ملخص للفقرة التالية:\n[[section]]\nحاول استخدام الكلمات الرئيسية التالية إذا كان ذلك ممكناً: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "يرجى كتابة TL; DR للنص التالي:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "يرجى كتابة TL; DR للنص التالي:\n[[text]]\nحاول استخدام الكلمات الرئيسية التالية إذا كان ذلك ممكناً: [[keywords]]",
            ],
        ],
    ],
    'ko' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "다음 주제에 대한 기사 제목을 작성하세요:\n[[description]]\n기사에는 다음 섹션이 포함됩니다:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "다음 주제에 대한 기사 제목을 작성하세요:\n[[description]]\n기사에는 다음 섹션이 포함됩니다:\n[[section-headlines]]\n다음 키워드를 사용할 수 있다면 사용해보세요: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "다음 주제에 대한 기사 소개를 작성하세요:\n[[description]]\n기사에는 다음 섹션이 포함됩니다:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "다음 주제에 대한 기사 소개를 작성하세요:\n[[description]]\n기사에는 다음 섹션이 포함됩니다:\n[[section-headlines]]\n다음 키워드를 사용할 수 있다면 사용해보세요: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "다음 주제에 대한 가능한 섹션 제목 목록을 작성하세요:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "다음 주제에 대한 가능한 섹션 제목 목록을 작성하세요:\n[[description]]\n다음 키워드를 사용할 수 있다면 사용해보세요: [[keywords]]",
            ],
            'section' => [
                'prompt' => "다음 주제에 대한 기사를 작성하고 있습니다:\n[[description]]\n\n기사의 일부로 다음 섹션을 작성하세요:\n[[section-headline]]\n\n섹션 내용:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "다음 주제에 대한 기사를 작성하고 있습니다:\n[[description]]\n\n기사의 일부로 다음 섹션을 작성하세요:\n[[section-headline]]\n\n다음 키워드를 사용할 수 있다면 사용해보세요: [[keywords]]\n\n섹션 내용:",
            ],
            'article-conclusion' => [
                'prompt' => "다음 주제에 대한 기사 결론을 작성하세요:\n[[description]]\n기사에는 다음 섹션이 포함됩니다:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "다음 주제에 대한 기사 결론을 작성하세요:\n[[description]]\n기사에는 다음 섹션이 포함됩니다:\n[[section-headlines]]\n다음 키워드를 사용할 수 있다면 사용해보세요: [[keywords]]",
            ],
            'image' => [
                'prompt' => "다음 내용에 가장 적합한 이미지를 선택하세요:\n\n [[text]]\n\n---\n이미지 설명은 다음과 같이 6개의 단어로 구성되어야 합니다:\n",
            ],
            'section-summary' => [
                'prompt' => "다음 섹션에 대한 요약을 작성하세요:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "다음 섹션에 대한 요약을 작성하세요:\n[[section]]\n다음 키워드를 사용할 수 있다면 사용해보세요: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "다음 내용에 대한 TL; DR을 작성하세요:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "다음 내용에 대한 TL; DR을 작성하세요:\n[[text]]\n다음 키워드를 사용할 수 있다면 사용해보세요: [[keywords]]",
            ],
        ],
    ],

    'hi' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "कृपया निम्न विषय पर लेख शीर्षक लिखें:\n[[description]]\nलेख में निम्न अनुभाग हैं:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "कृपया निम्न विषय पर लेख शीर्षक लिखें:\n[[description]]\nलेख में निम्न अनुभाग हैं:\n[[section-headlines]]\nयदि यह संभव है तो निम्न शब्दों का उपयोग करें: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "कृपया निम्न विषय पर लेख परिचय लिखें:\n[[description]]\nलेख में निम्न अनुभाग हैं:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "कृपया निम्न विषय पर लेख परिचय लिखें:\n[[description]]\nलेख में निम्न अनुभाग हैं:\n[[section-headlines]]\nयदि यह संभव है तो निम्न शब्दों का उपयोग करें: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "कृपया निम्न विषय पर संभव अनुभाग शीर्षक सूची लिखें:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "कृपया निम्न विषय पर संभव अनुभाग शीर्षक सूची लिखें:\n[[description]]\nयदि यह संभव है तो निम्न शब्दों का उपयोग करें: [[keywords]]",
            ],
            'section' => [
                'prompt' => "कृपया निम्न विषय पर लेख लिख रहे हैं:\n[[description]]\nकृपया निम्न अनुभाग लिखें:\n[[section-headline]]\n\nअनुभाग सामग्री:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "कृपया निम्न विषय पर लेख लिख रहे हैं:\n[[description]]\nकृपया निम्न अनुभाग लिखें:\n[[section-headline]]\n\nयदि यह संभव है तो निम्न शब्दों का उपयोग करें: [[keywords]]\n\nअनुभाग सामग्री:",
            ],
            'article-conclusion' => [
                'prompt' => "कृपया निम्न विषय पर लेख समापन लिखें:\n[[description]]\nलेख में निम्न अनुभाग हैं:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "कृपया निम्न विषय पर लेख समापन लिखें:\n[[description]]\nलेख में निम्न अनुभाग हैं:\n[[section-headlines]]\nयदि यह संभव है तो निम्न शब्दों का उपयोग करें: [[keywords]]",
            ],
            'section-summary' => [
                'prompt' => "कृपया निम्न अनुभाग का सारांश लिखें:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "कृपया निम्न अनुभाग का सारांश लिखें:\n[[section]]\nयदि यह संभव है तो निम्न शब्दों का उपयोग करें: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "कृपया निम्न विषय पर लेख का एक लाइन समापन लिखें:\n[[description]]\nलेख में निम्न अनुभाग हैं:\n[[section-headlines]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "कृपया निम्न विषय पर लेख का एक लाइन समापन लिखें:\n[[description]]\nलेख में निम्न अनुभाग हैं:\n[[section-headlines]]\nयदि यह संभव है तो निम्न शब्दों का उपयोग करें: [[keywords]]",
            ],
        ],
    ],
    'id' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Buat judul untuk artikel yang membahas topik berikut:\n[[description]]\nArtikel akan termasuk bagian berikut:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Buat judul untuk artikel yang membahas topik berikut:\n[[description]]\nArtikel akan termasuk bagian berikut:\n[[section-headlines]]\nCoba gunakan kata kunci seo berikut jika memungkinkan: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Tulis pengantar untuk artikel yang membahas topik berikut:\n[[description]]\nArtikel termasuk bagian berikut:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Tulis pengantar untuk artikel yang membahas topik berikut:\n[[description]]\nArtikel termasuk bagian berikut:\n[[section-headlines]]\nCoba gunakan kata kunci seo berikut jika memungkinkan: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Sarankan daftar [[number-of-headlines]] judul bagian yang mungkin untuk artikel yang akan membahas topik berikut:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Sarankan daftar [[number-of-headlines]] judul bagian yang mungkin untuk artikel yang akan membahas topik berikut:\n[[description]]\nCoba gunakan kata kunci seo berikut jika memungkinkan: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Saya sedang menulis artikel tentang topik berikut:\n[[description]]\n\nSebagai bagian dari artikel ini, tulis bagian teks yang membahas berikut: [[section-headline]]\n\nBagian teks tanpa judul:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Saya sedang menulis artikel tentang topik berikut:\n[[description]]\n\nSebagai bagian dari artikel ini, tulis bagian teks yang membahas berikut: [[section-headline]]\n\nCoba gunakan kata kunci seo berikut jika memungkinkan: [[keywords]]\n\nBagian teks tanpa judul:",
            ],
            'article-conclusion' => [
                'prompt' => "Tulis kesimpulan untuk artikel yang membahas topik berikut:\n[[description]]\nArtikel termasuk bagian berikut:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Tulis kesimpulan untuk artikel yang membahas topik berikut:\n[[description]]\nArtikel termasuk bagian berikut:\n[[section-headlines]]\nCoba gunakan kata kunci seo berikut jika memungkinkan: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Jelaskan gambar yang paling cocok untuk teks berikut:\n\n [[text]]\n\n---\nDeskripsi gambar kreatif dalam satu kalimat 6 kata:\n",
            ],
            'section-summary' => [
                'prompt' => "Tulis ringkasan bagian singkat untuk teks bagian artikel berikut:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Tulis ringkasan bagian singkat untuk teks bagian artikel berikut:\n[[section]]\nCoba gunakan kata kunci seo berikut jika memungkinkan: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Tulis TL;DR untuk teks berikut:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Tulis TL;DR untuk teks berikut:\n[[text]]\nCoba gunakan kata kunci seo berikut jika memungkinkan: [[keywords]]",
            ],
        ]
    ],
    'sv' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Skriv en titel för en artikel som behandlar följande ämne:\n[[description]]\nArtikeln kommer att innehålla följande avsnitt:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Skriv en titel för en artikel som behandlar följande ämne:\n[[description]]\nArtikeln kommer att innehålla följande avsnitt:\n[[section-headlines]]\nFörsök använda följande SEO-nyckelord om möjligt: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Skriv en introduktion för en artikel som behandlar följande ämne:\n[[description]]\nArtikeln kommer att innehålla följande avsnitt:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Skriv en introduktion för en artikel som behandlar följande ämne:\n[[description]]\nArtikeln kommer att innehålla följande avsnitt:\n[[section-headlines]]\nFörsök använda följande SEO-nyckelord om möjligt: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Föreslå en lista med [[number-of-headlines]] rubriker för avsnitt som kan vara relevant för en artikel som behandlar följande ämne:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Föreslå en lista med [[number-of-headlines]] rubriker för avsnitt som kan vara relevant för en artikel som behandlar följande ämne:\n[[description]]\nFörsök använda följande SEO-nyckelord om möjligt: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Jag skriver en artikel om följande ämne:\n[[description]]\n\nSom en del av artikeln, skriv en text som behandlar följande rubrik: [[section-headline]]\n\nText utan rubrik:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Jag skriver en artikel om följande ämne:\n[[description]]\n\nSom en del av artikeln, skriv en text som behandlar följande rubrik: [[section-headline]]\n\nFörsök använda följande SEO-nyckelord om möjligt: [[keywords]]\n\nText utan rubrik:",
            ],
            'article-conclusion' => [
                'prompt' => "Skriv en slutsats för en artikel som behandlar följande ämne:\n[[description]]\nArtikeln kommer att innehålla följande avsnitt:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Skriv en slutsats för en artikel som behandlar följande ämne:\n[[description]]\nArtikeln kommer att innehålla följande avsnitt:\n[[section-headlines]]\nFörsök använda följande SEO-nyckelord om möjligt: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Beskriv vilket bild som passar bäst för följande text:\n\n [[text]]\n\n---\nKreativ bildbeskrivning i en mening 6 ord:\n",
            ],
            'section-summary' => [
                'prompt' => "Skriv en kort sammanfattning av avsnittet för följande artikeltext:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Skriv en kort sammanfattning av avsnittet för följande artikeltext:\n[[section]]\nFörsök använda följande SEO-nyckelord om möjligt: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Skriv TL;DR för följande text:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Skriv TL;DR för följande text:\n[[text]]\nFörsök använda följande SEO-nyckelord om möjligt: [[keywords]]",
            ],
        ]
    ],
    'da' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Skriv en titel til en artikel om følgende emne:\n[[description]]\nArtiklen vil indeholde følgende afsnit:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Skriv en titel til en artikel om følgende emne:\n[[description]]\nArtiklen vil indeholde følgende afsnit:\n[[section-headlines]]\nPrøv at bruge følgende SEO-nøgleord, hvis det er muligt: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Skriv en introduktion til en artikel om følgende emne:\n[[description]]\nArtiklen vil indeholde følgende afsnit:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Skriv en introduktion til en artikel om følgende emne:\n[[description]]\nArtiklen vil indeholde følgende afsnit:\n[[section-headlines]]\nPrøv at bruge følgende SEO-nøgleord, hvis det er muligt: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Foreslå en liste med [[number-of-headlines]] overskrifter til afsnit, der kan være relevante for en artikel om følgende emne:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Foreslå en liste med [[number-of-headlines]] overskrifter til afsnit, der kan være relevante for en artikel om følgende emne:\n[[description]]\nPrøv at bruge følgende SEO-nøgleord, hvis det er muligt: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Jeg skriver en artikel om følgende emne:\n[[description]]\n\nSom en del af artiklen, skriv en tekst, der behandler følgende overskrift: [[section-headline]]\n\nTekst uden overskrift:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => 'Jeg skriver en artikel om følgende emne:\n[[description]]\n\nSom en del af artiklen, skal du skrive en tekst, der behandler følgende overskrift: [[section-headline]]\n\nPrøv at bruge følgende SEO-nøgleord, hvis det er muligt: [[keywords]]\n\nTekst uden overskrift:',
            ],
            'article-conclusion' => [
                'prompt' => "Skriv en konklusion til en artikel om følgende emne:\n[[description]]\nArtiklen vil indeholde følgende afsnit:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Skriv en konklusion til en artikel om følgende emne:\n[[description]]\nArtiklen vil indeholde følgende afsnit:\n[[section-headlines]]\nPrøv at bruge følgende SEO-nøgleord, hvis det er muligt: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Beskriv hvilket billede, der passer bedst til følgende tekst:\n\n [[text]]\n\n---\nKreativ billedbeskrivelse i en sætning 6 ord:\n",
            ],
            'section-summary' => [
                'prompt' => "Skriv en kort opsummering af afsnittet for følgende artikeltekst:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Skriv en kort opsummering af afsnittet for følgende artikeltekst:\n[[section]]\nPrøv at bruge følgende SEO-nøgleord, hvis det er muligt: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Skriv TL;DR for følgende tekst:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Skriv TL;DR for følgende tekst:\n[[text]]\nPrøv at bruge følgende SEO-nøgleord, hvis det er muligt: [[keywords]]",
            ],
        ],
    ],
    'fi' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Kirjoita artikkelin otsikko seuraavasta aiheesta:\n[[description]]\nArtikkeli sisältää seuraavat osiot:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Kirjoita artikkelin otsikko seuraavasta aiheesta:\n[[description]]\nArtikkeli sisältää seuraavat osiot:\n[[section-headlines]]\nYritä käyttää seuraavia SEO-avainsanoja, jos mahdollista: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Kirjoita artikkelin esipuhe seuraavasta aiheesta:\n[[description]]\nArtikkeli sisältää seuraavat osiot:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Kirjoita artikkelin esipuhe seuraavasta aiheesta:\n[[description]]\nArtikkeli sisältää seuraavat osiot:\n[[section-headlines]]\nYritä käyttää seuraavia SEO-avainsanoja, jos mahdollista: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Ehdota lista [[number-of-headlines]] otsikoista, jotka voivat olla relevantteja artikkelille seuraavasta aiheesta:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Ehdota lista [[number-of-headlines]] otsikoista, jotka voivat olla relevantteja artikkelille seuraavasta aiheesta:\n[[description]]\nYritä käyttää seuraavia SEO-avainsanoja, jos mahdollista: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Kirjoitan artikkelin seuraavasta aiheesta:\n[[description]]\n\nOsana artikkelia kirjoita teksti, joka käsittelee seuraavaa otsikkoa: [[section-headline]]\n\nTeksti ilman otsikkoa:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => 'Kirjoitan artikkelin seuraavasta aiheesta:\n[[description]]\n\nOsana artikkelia kirjoita teksti, joka käsittelee seuraavaa otsikkoa: [[section-headline]]\n\nYritä käyttää seuraavia SEO-avainsanoja, jos mahdollista: [[keywords]]\n\nTeksti ilman otsikkoa:',
            ],
            'article-conclusion' => [
                'prompt' => "Kirjoita artikkelin johtopäätös seuraavasta aiheesta:\n[[description]]\nArtikkeli sisältää seuraavat osiot:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Kirjoita artikkelin johtopäätös seuraavasta aiheesta:\n[[description]]\nArtikkeli sisältää seuraavat osiot:\n[[section-headlines]]\nYritä käyttää seuraavia SEO-avainsanoja, jos mahdollista: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Kuvaile, mikä kuva sopii parhaiten seuraavaan tekstiin:\n\n [[text]]\n\n---\nLuova kuvaus kuvaan yhdessä lauseessa 6 sanaa:\n",
            ],
            'section-summary' => [
                'prompt' => "Kirjoita lyhyt yhteenveto osiosta seuraavalle artikkelitekstille:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Kirjoita lyhyt yhteenveto osiosta seuraavalle artikkelitekstille:\n[[section]]\nYritä käyttää seuraavia SEO-avainsanoja, jos mahdollista: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Kirjoita TL;DR seuraavalle tekstille:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Kirjoita TL;DR seuraavalle tekstille:\n[[text]]\nYritä käyttää seuraavia SEO-avainsanoja, jos mahdollista: [[keywords]]",
            ],
        ]
    ],
    'no' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Skriv en artikkeloverskrift for følgende emne:\n[[description]]\nArtikkelen inneholder følgende seksjoner:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Skriv en artikkeloverskrift for følgende emne:\n[[description]]\nArtikkelen inneholder følgende seksjoner:\n[[section-headlines]]\nPrøv å bruke følgende SEO-nøkkelord, hvis det er mulig: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Skriv en artikkelintroduksjon for følgende emne:\n[[description]]\nArtikkelen inneholder følgende seksjoner:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Skriv en artikkelintroduksjon for følgende emne:\n[[description]]\nArtikkelen inneholder følgende seksjoner:\n[[section-headlines]]\nPrøv å bruke følgende SEO-nøkkelord, hvis det er mulig: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Foreslå en liste med [[number-of-headlines]] overskrifter som kan være relevante for artikkelen for følgende emne:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Foreslå en liste med [[number-of-headlines]] overskrifter som kan være relevante for artikkelen for følgende emne:\n[[description]]\nPrøv å bruke følgende SEO-nøkkelord, hvis det er mulig: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Jeg skriver en artikkel om følgende emne:\n[[description]]\n\nSom en del av artikkelen, skriv tekst som behandler følgende overskrift: [[section-headline]]\n\nTekst uten overskrift:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => 'Jeg skriver en artikkel om følgende emne:\n[[description]]\n\nSom en del av artikkelen, skriv tekst som behandler følgende overskrift: [[section-headline]]\n\nPrøv å bruke følgende SEO-nøkkelord, hvis det er mulig: [[keywords]]\n\nTekst uten overskrift:',
            ],
            'article-conclusion' => [
                'prompt' => "Skriv en artikkelkonklusjon for følgende emne:\n[[description]]\nArtikkelen inneholder følgende seksjoner:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Skriv en artikkelkonklusjon for følgende emne:\n[[description]]\nArtikkelen inneholder følgende seksjoner:\n[[section-headlines]]\nPrøv å bruke følgende SEO-nøkkelord, hvis det er mulig: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Beskriv hva slags bilde som passer best til følgende tekst:\n\n [[text]]\n\n---\nKreativ beskrivelse av bildet i ett setning med 6 ord:",
            ],
            'section-summary' => [
                'prompt' => "Skriv en kort oppsummering av seksjonen for følgende artikkeltekst:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Skriv en kort oppsummering av seksjonen for følgende artikkeltekst:\n[[section]]\nPrøv å bruke følgende SEO-nøkkelord, hvis det er mulig: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Skriv TL;DR for følgende tekst:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Skriv TL;DR for følgende tekst:\n[[text]]\nPrøv å bruke følgende SEO-nøkkelord, hvis det er mulig: [[keywords]]",
            ],
        ],
    ],
    'ro' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Scrie un titlu de articol pentru următorul subiect:\n[[description]]\nArticolul conține următoarele secțiuni:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Scrie un titlu de articol pentru următorul subiect:\n[[description]]\nArticolul conține următoarele secțiuni:\n[[section-headlines]]\nÎncearcă să folosești următoarele cuvinte cheie SEO, dacă este posibil: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Scrie o introducere de articol pentru următorul subiect:\n[[description]]\nArticolul conține următoarele secțiuni:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Scrie o introducere de articol pentru următorul subiect:\n[[description]]\nArticolul conține următoarele secțiuni:\n[[section-headlines]]\nÎncearcă să folosești următoarele cuvinte cheie SEO, dacă este posibil: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Propune o listă cu [[number-of-headlines]] titluri care ar putea fi relevante pentru articolul pentru următorul subiect:\n[[description]]",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Propune o listă cu [[number-of-headlines]] titluri care ar putea fi relevante pentru articolul pentru următorul subiect:\n[[description]]\nÎncearcă să folosești următoarele cuvinte cheie SEO, dacă este posibil: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Scriu un articol despre următorul subiect:\n[[description]]\n\nCa parte a articolului, scrie text care tratează următoarea secțiune: [[section-headline]]\n\nText fără secțiune:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => 'Scriu un articol despre următorul subiect:\n[[description]]\n\nCa parte a articolului, scrie text care tratează următoarea secțiune: [[section-headline]]\n\nÎncearcă să folosești următoarele cuvinte cheie SEO, dacă este posibil: [[keywords]]\n\nText fără secțiune:',
            ],
            'article-conclusion' => [
                'prompt' => "Scrie o concluzie de articol pentru următorul subiect:\n[[description]]\nArticolul conține următoarele secțiuni:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Scrie o concluzie de articol pentru următorul subiect:\n[[description]]\nArticolul conține următoarele secțiuni:\n[[section-headlines]]\nÎncearcă să folosești următoarele cuvinte cheie SEO, dacă este posibil: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Descrie ce fel de imagine ar fi potrivită pentru următorul text:\n\n [[text]]\n\n---\nDescriere creativă a imaginii într-o propoziție cu 6 cuvinte:",
            ],
            'section-summary' => [
                'prompt' => "Scrie un rezumat al secțiunii pentru următorul text de articol:\n[[section]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Scrie un rezumat al secțiunii pentru următorul text de articol:\n[[section]]\nÎncearcă să folosești următoarele cuvinte cheie SEO, dacă este posibil: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Scrie TL;DR pentru următorul text:\n[[text]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Scrie TL;DR pentru următorul text:\n[[text]]\nÎncearcă să folosești următoarele cuvinte cheie SEO, dacă este posibil: [[keywords]]",
            ],
        ],
    ],
    'ka' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "დაწერეთ სტატიის სათაური შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "დაწერეთ სტატიის სათაური შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]\nსცადეთ გამოიყენოთ შემდეგი SEO სიტყვები, თუ შეგიძლიათ: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "დაწერეთ სტატიის შესავალი შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "დაწერეთ სტატიის შესავალი შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]\nსცადეთ გამოიყენოთ შემდეგი SEO სიტყვები, თუ შეგიძლიათ: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "შემოსავალი [[description]] თემაზე დახარჯვის შესახებ შეიძლება შეიცავდეს [[number-of-headlines]] სათაურს.",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "შეიტანე [[number-of-headlines]] სათაური, რომლებიც შეიძლება იყოს შესაბამისი მიმოხილვებისთვის შემდეგ თემაზე:\n[[description]]\nცადე, რომ გამოიყენებთ შემდეგი SEO სიტყვებს, თუ შეგიძლიათ: [[keywords]]",
            ],
            'section' => [
                'prompt' => "დაწერეთ სექციის ტექსტი შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "დაწერეთ სექციის ტექსტი შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]\nსცადეთ გამოიყენოთ შემდეგი SEO სიტყვები, თუ შეგიძლიათ: [[keywords]]",
            ],
            'article-conclusion' => [
                'prompt' => "დაწერეთ სტატიის ბოლო შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "დაწერეთ სტატიის ბოლო შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]\nსცადეთ გამოიყენოთ შემდეგი SEO სიტყვები, თუ შეგიძლიათ: [[keywords]]",
            ],
            'image' => [
                'prompt' => "დაწერეთ სურათის აღწერა შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]",
            ],
            'section-summary' => [
                'prompt' => "დაწერეთ სექციის შეჯამება შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "დაწერეთ სექციის შეჯამება შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]\nსცადეთ გამოიყენოთ შემდეგი SEO სიტყვები, თუ შეგიძლიათ: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "დაწერეთ სტატიის შეჯამება შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "დაწერეთ სტატიის შეჯამება შემდეგი თემისთვის:\n[[description]]\nსტატია შეიცავს შემდეგი სექციებს:\n[[section-headlines]]\nსცადეთ გამოიყენოთ შემდეგი SEO სიტყვები, თუ შეგიძლიათ: [[keywords]]",
            ],
        ],
    ],
    'vi' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]\nHãy thử sử dụng các từ khóa SEO sau, nếu có thể: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]\nHãy thử sử dụng các từ khóa SEO sau, nếu có thể: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Đề xuất một danh sách [[number-of-headlines]] tiêu đề có thể của các phần cho một bài viết sẽ bao gồm các chủ đề sau:\n[[description]]\n\nTiêu đề của các phần:"
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Đề xuất một danh sách [[number-of-headlines]] tiêu đề có thể của các phần cho một bài viết sẽ bao gồm các chủ đề sau:\n[[description]]\nHãy thử sử dụng các từ khóa SEO sau, nếu có thể: [[keywords]]\n\nTiêu đề của các phần:"
            ],
            'section' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]\nHãy thử sử dụng các từ khóa SEO sau, nếu có thể: [[keywords]]",
            ],
            'article-conclusion' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]\nHãy thử sử dụng các từ khóa SEO sau, nếu có thể: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]",
            ],
            'section-summary' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]\nHãy thử sử dụng các từ khóa SEO sau, nếu có thể: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Viết mô tả cho ảnh:\n[[description]]\nBài viết có các phần sau:\n[[section-headlines]]\nHãy thử sử dụng các từ khóa SEO sau, nếu có thể: [[keywords]]",
            ],
        ],
    ],
    'hu' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]\nPróbáld ki a következő SEO kulcsszavakat, ha lehet: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]\nPróbáld ki a következő SEO kulcsszavakat, ha lehet: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Suggest a list of [[number-of-headlines]] possible headlines of sections for an article that will cover the following topic:\n[[description]]\n\nSection headlines:",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Suggest a list of [[number-of-headlines]] possible headlines of sections for an article that will cover the following topic:\n[[description]]\nTry to use the following seo keywords when possible: [[keywords]]\n\nSection headlines:",
            ],
            'section' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]\nPróbáld ki a következő SEO kulcsszavakat, ha lehet: [[keywords]]",
            ],
            'article-conclusion' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]\nPróbáld ki a következő SEO kulcsszavakat, ha lehet: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]",
            ],
            'section-summary' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]\nPróbáld ki a következő SEO kulcsszavakat, ha lehet: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Írj leírást a képhez:\n[[description]]\nA cikk tartalma:\n[[section-headlines]]\nPróbáld ki a következő SEO kulcsszavakat, ha lehet: [[keywords]]",
            ],
        ],
    ],
    'bg' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]\nОпитайте се да използвате следните ключови думи за SEO, ако е възможно: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]\nОпитайте се да използвате следните ключови думи за SEO, ако е възможно: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Предложете списък със [[number-of-headlines]] възможни заглавия на секции за статия, която ще обхване следната тема:\n[[description]]\n\nЗаглавия на секции:",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Предложете списък със [[number-of-headlines]] възможни заглавия на секции за статия, която ще обхване следната тема:\n[[description]]\nОпитайте се да използвате следните ключови думи за SEO, ако е възможно: [[keywords]]\n\nЗаглавия на секции:",
            ],
            'section' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]\nОпитайте се да използвате следните ключови думи за SEO, ако е възможно: [[keywords]]",
            ],
            'article-conclusion' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]\nОпитайте се да използвате следните ключови думи за SEO, ако е възможно: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]",
            ],
            'section-summary' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]\nОпитайте се да използвате следните ключови думи за SEO, ако е възможно: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Напишете описание на изображението:\n[[description]]\nСтатията съдържа следните части:\n[[section-headlines]]\nОпитайте се да използвате следните ключови думи за SEO, ако е възможно: [[keywords]]",
            ],
        ],
    ],
    'el' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]\nΠροσπαθήστε να χρησιμοποιήσετε τις ακόλουθες λέξεις-κλειδιά για SEO, αν είναι δυνατόν: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]\nΠροσπαθήστε να χρησιμοποιήσετε τις ακόλουθες λέξεις-κλειδιά για SEO, αν είναι δυνατόν: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Προτείνετε μια λίστα με [[number-of-headlines]] πιθανούς τίτλους τμημάτων για ένα άρθρο που θα καλύπτει το ακόλουθο θέμα:\n[[description]]\n\nΤίτλοι τμημάτων:"
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Προτείνετε μια λίστα με [[number-of-headlines]] πιθανούς τίτλους τμημάτων για ένα άρθρο που θα καλύπτει το ακόλουθο θέμα:\n[[description]]\n\nΤίτλοι τμημάτων:\n\nΠροσπαθήστε να χρησιμοποιήσετε τις ακόλουθες λέξεις-κλειδιά για SEO, αν είναι δυνατόν: [[keywords]]",
            ],
            'section' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]\nΠροσπαθήστε να χρησιμοποιήσετε τις ακόλουθες λέξεις-κλειδιά για SEO, αν είναι δυνατόν: [[keywords]]",
            ],
            'article-conclusion' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]\nΠροσπαθήστε να χρησιμοποιήσετε τις ακόλουθες λέξεις-κλειδιά για SEO, αν είναι δυνατόν: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]",
            ],
            'section-summary' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]\nΠροσπαθήστε να χρησιμοποιήσετε τις ακόλουθες λέξεις-κλειδιά για SEO, αν είναι δυνατόν: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Περιγράψτε την εικόνα:\n[[description]]\nΤο άρθρο περιέχει τα εξής τμήματα:\n[[section-headlines]]\nΠροσπαθήστε να χρησιμοποιήσετε τις ακόλουθες λέξεις-κλειδιά για SEO, αν είναι δυνατόν: [[keywords]]",
            ],
        ],
    ],
    'fa' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "یک عنوان برای مقاله‌ای که در مورد موضوع زیر صحبت می‌کند، تولید کنید:\n[[description]]\nاین مقاله شامل بخش‌های زیر می‌شود:\n[[section-headlines]]\n\nعنوان:",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "یک عنوان برای مقاله‌ای که در مورد موضوع زیر صحبت می‌کند، تولید کنید:\n[[description]]\nاین مقاله شامل بخش‌های زیر می‌شود:\n[[section-headlines]]\nوقتی امکانش هست از کلمات کلیدی سئو زیر استفاده کنید: [[keywords]]\n\nعنوان:",
            ],
            'article-intro' => [
                'prompt' => "برای مقاله‌ای که در مورد موضوع زیر صحبت می‌کند، مقدمه‌ای بنویسید:\n[[description]]\nاین مقاله شامل بخش‌های زیر می‌شود:\n[[section-headlines]]\n\nمقدمه مقاله:",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "برای مقاله‌ای که در مورد موضوع زیر صحبت می‌کند، مقدمه‌ای بنویسید:\n[[description]]\nاین مقاله شامل بخش‌های زیر می‌شود:\n[[section-headlines]]\nوقتی امکانش هست از کلمات کلیدی سئو زیر استفاده کنید: [[keywords]]\n\nمقدمه مقاله:",
            ],
            'section-headlines' => [
                'prompt' => "یک لیست از عناوین ممکن برای [[number-of-headlines]] بخش مختلف یک مقاله که در مورد موضوع زیر صحبت می‌کند، پیشنهاد دهید:\n[[description]]\n\nعناوین بخش‌ها:",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "لیستی از [[number-of-headlines]] عنوان بخش برای مقاله‌ای که درباره موضوع زیر صحبت می‌کند، پیشنهاد دهید:\n[[description]]\nهنگام امکان‌پذیر بودن، از کلمات کلیدی سئو زیر استفاده کنید: [[keywords]]\n\nعناوین بخش‌ها:",
            ],
            'section' => [
                'prompt' => "من در حال نوشتن یک مقاله در مورد موضوع زیر هستم:\n[[description]]\n\nبه عنوان بخشی از این مقاله، یک بخش متنی بنویسید که در مورد موارد زیر بحث کند: [[section-headline]]\n\nمتن بدون عنوان بخش:",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "من در حال نوشتن یک مقاله در مورد موضوع زیر هستم:\n[[description]]\n\nبه عنوان بخشی از این مقاله، یک بخش متنی بنویسید که در مورد موارد زیر بحث کند: [[section-headline]]\n\nسعی کنید از کلمات کلیدی سئو زیر استفاده کنید: [[keywords]]\n\nمتن بدون عنوان بخش:",
            ],
            'article-conclusion' => [
                'prompt' => "نتیجه گیری مقاله ای که در مورد موضوع زیر صحبت می کند را بنویسید:\n[[description]]\nمقاله شامل بخش های زیر است:\n[[section-headlines]]\n\nنتیجه گیری مقاله:",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "نتیجه گیری مقاله ای که در مورد موضوع زیر صحبت می کند را بنویسید:\n[[description]]\nمقاله شامل بخش های زیر است:\n[[section-headlines]]\nسعی کنید از کلمات کلیدی سئو زیر استفاده کنید: [[keywords]]\n\nنتیجه گیری مقاله:",
            ],
            'image' => [
                'prompt' => "تصویر مناسبی برای این متن شامل چه توصیفاتی می شود؟:\n\n [[text]]\n\n---\nتوصیف تصویر خلاقانه در یک جمله شامل 6 کلمه:\n",
            ],
            'section-summary' => [
                'prompt' => "خلاصه کوتاهی از متن بخش مقاله زیر بنویسید:\n[[section]]\n\nخلاصه بخش:",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "برای متن بخش مقاله زیر خلاصه کوتاهی بنویسید:\n[[section]]\nهنگام امکان پذیر بودن، سعی کنید از کلمات کلیدی سئو زیر استفاده کنید:\n[[keywords]]\n\nخلاصه بخش:",
            ],
            'tldr' => [
                'prompt' => "برای متن زیر یک TL;DR بنویسید:\n[[text]]\n\nTL;DR:",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "برای متن زیر یک TL;DR بنویسید:\n[[text]]\nهنگام امکان پذیر بودن، سعی کنید از کلمات کلیدی سئو زیر استفاده کنید:\n[[keywords]]\n\nTL;DR:",
            ],
        ],
    ],
    'sk' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]\nZohľadnite kľúčové slová pre SEO: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]\nZohľadnite kľúčové slová pre SEO: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Navrhnite zoznam [[number-of-headlines]] možných titulkov sekcií pre článok, ktorý bude kryť nasledujúce témy:\n[[description]]\n\nTitulky sekcií:",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Navrhnite zoznam [[number-of-headlines]] možných titulkov sekcií pre článok, ktorý bude kryť nasledujúce témy:\n[[description]]\nZohľadnite kľúčové slová pre SEO: [[keywords]] \n\nTitulky sekcií:",
            ],
            'section' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]\nZohľadnite kľúčové slová pre SEO: [[keywords]]",
            ],
            'article-conclusion' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]\nZohľadnite kľúčové slová pre SEO: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'section-summary' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]\nZohľadnite kľúčové slová pre SEO: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]\nZohľadnite kľúčové slová pre SEO: [[keywords]]",
            ],
        ],
    ],
    'cs' => [
        'prompts' => [
            'article-title' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'article-title-with-seo-keywords' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]\nZohľadnite kľúčové slová pre SEO: [[keywords]]",
            ],
            'article-intro' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'article-intro-with-seo-keywords' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]\nZohľadnite kľúčové slová pre SEO: [[keywords]]",
            ],
            'section-headlines' => [
                'prompt' => "Navrhněte seznam [[number-of-headlines]] možných nadpisů sekce pro článek, který bude pokrývat následující témata:\n[[description]]\n\nNadpisy sekce:",
            ],
            'section-headlines-with-seo-keywords' => [
                'prompt' => "Navrhněte seznam [[number-of-headlines]] možných nadpisů sekce pro článek, který bude pokrývat následující témata:\n[[description]]\nZohľadněte klíčová slova pro SEO: [[keywords]] \n\nNadpisy sekce:",
            ],
            'section' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'section-with-seo-keywords' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]\nZohľadnite kľúčové slová pre SEO: [[keywords]]",
            ],
            'article-conclusion' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'article-conclusion-with-seo-keywords' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]\nZohľadnite kľúčové slová pre SEO: [[keywords]]",
            ],
            'image' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'section-summary' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'section-summary-with-seo-keywords' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]\nZohľadnite kľúčové slová pre SEO: [[keywords]]",
            ],
            'tldr' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]",
            ],
            'tldr-with-seo-keywords' => [
                'prompt' => "Popis obrázka:\n[[description]]\nSekcie v článku:\n[[section-headlines]]\nZohľadnite kľúčové slová pre SEO: [[keywords]]",
            ],
        ],
    ]
];
