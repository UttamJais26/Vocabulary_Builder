
const searchInput = document.getElementById("searchInput");

const searchBtn = document.getElementById("searchBtn");

const result = document.getElementById("result");


searchBtn.addEventListener("click", searchWord);


async function searchWord() {

    const word = searchInput.value.trim();

    if (word === "") {
        result.textContent = "Please enter a word.";
        return;
    }

    result.textContent = "Searching...";

    try {

        const response = await fetch(
            `https://api.dictionaryapi.dev/api/v2/entries/en/${encodeURIComponent(word)}`
        );

        if (!response.ok) {
            throw new Error("Word not found");
        }

        const data = await response.json();

        result.replaceChildren();

        const heading = document.createElement("h2");
        heading.textContent = data[0].word;

        result.appendChild(heading);

        const phonetic = document.createElement("p");
        phonetic.textContent = "Pronunciation: " +
            (data[0].phonetic || "Not available");

        result.appendChild(phonetic);

        data[0].meanings.forEach(function(meaning) {

            const part = document.createElement("h3");
            part.textContent = meaning.partOfSpeech;

            result.appendChild(part);

            meaning.definitions.forEach(function(definition) {

                const paragraph = document.createElement("p");

                paragraph.textContent = definition.definition;

                result.appendChild(paragraph);

            });

        });

    }

    catch(error) {

        result.textContent = "Word not found. Please try another word.";

    }

}
