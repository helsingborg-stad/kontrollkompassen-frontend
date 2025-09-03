// source/ts/main.ts

interface ResponseData {
  orgno: number | null
}

class App {
  constructor() {
    addEventListener("DOMContentLoaded", this._domReady.bind(this))
  }

  private _domReady(): void {
    console.log("DOM fully loaded and parsed")
    const form = document.getElementById("check-orgno-form")
    form && form.addEventListener("submit", this._handleSubmit.bind(this))
  }

  private async _handleSubmit(event: Event): Promise<void> {
    try {
      event.preventDefault()
      const form = event.target as HTMLFormElement
      const orgno = (await this._sendForm(new FormData(form))).orgno
      console.log("Response orgno:", orgno)

    } catch (error) {
      const errorEl = document.getElementById("error-message")
      if (errorEl && error instanceof Error) {
        errorEl.textContent = error.message
        errorEl.classList.remove("hidden")
      }
    }
  }

  private async _sendForm(data: FormData): Promise<ResponseData> {
    const resp = await fetch("/uppslag/json", {
      method: "POST",
      body: JSON.stringify(Object.fromEntries(data.entries())),
      headers: { "Content-Type": "application/json" },
    })

    if (!resp.ok) {
      const error = await resp.json()
      throw new Error(error.message || "Unknown error occurred")
    }

    return resp.json()
  }
}

(_ => new App())()
