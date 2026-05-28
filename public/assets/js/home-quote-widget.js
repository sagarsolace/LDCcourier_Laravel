document.addEventListener("DOMContentLoaded", function () {
    const API_KEY = "2L_YEszMXFOr8Nq3XBMnqgTZINiyxr305hnQeYuz-9Q";
    const ukPostcodeRegex = /[A-Z]{1,2}\d[A-Z\d]?\s*\d[A-Z]{2}/i;
    const cache = {};

    function fetchLocations(query) {
        if (cache[query]) {
            return Promise.resolve(cache[query]);
        }

        return fetch(
            `https://geocode.search.hereapi.com/v1/geocode?apiKey=${API_KEY}&q=${encodeURIComponent(query)}&in=countryCode:GBR&limit=5`
        )
            .then((res) => res.json())
            .then((data) => {
                cache[query] = data;
                return data;
            });
    }

    function setupAutocomplete(inputId) {
        const input = document.getElementById(inputId);
        if (!input) {
            return;
        }

        let timeout;
        let lastQuery = "";
        let list;

        input.addEventListener("input", function () {
            const query = input.value.trim();
            input.dataset.postcode = "";

            clearTimeout(timeout);
            timeout = setTimeout(async () => {
                if (query.length < 3 || query === lastQuery) {
                    return;
                }

                lastQuery = query;
                try {
                    const data = await fetchLocations(query);
                    if (data.items) {
                        showSuggestions(data.items);
                    }
                } catch (err) {
                    console.error(err);
                }
            }, 500);
        });

        input.addEventListener("blur", async function () {
            const value = input.value.trim();
            if (!value) {
                return;
            }

            const match = value.match(ukPostcodeRegex);
            if (!match) {
                return;
            }

            const postcode = match[0].toUpperCase().replace(/\s+/, " ").trim();
            input.dataset.postcode = postcode;

            try {
                const data = await fetchLocations(postcode);
                if (data.items && data.items.length > 0) {
                    const a = data.items[0].address;
                    input.value = [a.city, a.county, a.postalCode, a.countryName]
                        .filter(Boolean)
                        .join(", ");
                }
            } catch (err) {
                console.error(err);
            }
        });

        function showSuggestions(items) {
            if (list) {
                list.remove();
            }

            list = document.createElement("ul");
            list.className = "suggestions";

            items.forEach((item) => {
                const li = document.createElement("li");
                li.textContent = item.title;
                li.onclick = () => {
                    const a = item.address || {};
                    input.value = [a.houseNumber, a.street, a.city, a.postalCode, a.countryName]
                        .filter(Boolean)
                        .join(", ");
                    input.dataset.postcode = a.postalCode || "";
                    list.remove();
                };
                list.appendChild(li);
            });

            input.parentNode.appendChild(list);

            document.addEventListener("click", function handler(e) {
                if (!input.contains(e.target) && !list.contains(e.target)) {
                    list.remove();
                    document.removeEventListener("click", handler);
                }
            });
        }
    }

    setupAutocomplete("pickup");
    setupAutocomplete("delivery");

    const pickup = document.getElementById("pickup");
    const delivery = document.getElementById("delivery");
    const vehicle = document.getElementById("vehicle");
    const quoteButton = document.querySelector(".quote-btn");

    if (!pickup || !delivery || !vehicle || !quoteButton) {
        return;
    }

    quoteButton.addEventListener("click", () => {
        const p = pickup.value.trim();
        const d = delivery.value.trim();
        const v = vehicle.value;
        const pp = pickup.dataset.postcode;
        const dp = delivery.dataset.postcode;

        if (!p || !d || !v || !pp || !dp || pp === dp) {
            alert("Please fill all fields correctly");
            return;
        }

        const url =
            "https://portal.ldccourier.co.uk/order/where?" +
            "collection=" +
            encodeURIComponent(p) +
            "&delivery=" +
            encodeURIComponent(d) +
            "&collection_postcode=" +
            encodeURIComponent(pp) +
            "&delivery_postcode=" +
            encodeURIComponent(dp) +
            "&vehicle=" +
            encodeURIComponent(v);

        window.open(url, "_blank");
    });
});
