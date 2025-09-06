import type { AlpineComponent } from 'alpinejs'

interface EventDialogData {
    modalOpen: boolean
    isLoading: boolean
    eventData: { blogposts?: any[]; tag?: { page?: any } } | null
    error: string | null
    eventSlug: string
}

interface EventDialogMethods {
    openModal(): void
    closeModal(): void
    fetchEventData(): Promise<void>
    updateUrlParams(): void
    removeUrlParams(): void
    formatDate(dateString: string, options: Intl.DateTimeFormatOptions): string,
    hasBlogpostOrTags(): boolean
}

// Shared dialog component (single instance on page)
export function sharedEventDialog(): AlpineComponent<EventDialogData & EventDialogMethods> {
    return {
        modalOpen: false,
        isLoading: false,
        eventData: null,
        error: null,
        eventSlug: '',

        init() {
            // Listen for global event dialog open requests
            document.addEventListener('open-event-dialog', (event: Event) => {
                const customEvent = event as CustomEvent<{ slug: string }>
                this.eventSlug = customEvent.detail.slug
                this.openModal()
            })

            // Check if this event should be opened initially via URL params
            const urlParams = new URLSearchParams(window.location.search)
            const urlEventSlug = urlParams.get('event')

            if (urlEventSlug) {
                // Use nextTick to ensure the modal ref is available
                this.$nextTick(() => {
                    this.eventSlug = urlEventSlug
                    this.modalOpen = true
                })
            }

            // Watch modalOpen changes
            this.$watch('modalOpen', (value) => {
                if (value) {
                    const modal = this.$refs.eventModal as HTMLDialogElement
                    const closeButton = this.$refs.eventModalCloseButton as HTMLButtonElement | undefined;
                    if (modal) {
                        closeButton?.focus();
                        modal.showModal();
                        this.updateUrlParams()

                        // Fetch event data if not already loaded or if slug changed
                        if (!this.eventData || (this.eventData as any).slug !== this.eventSlug) {
                            this.fetchEventData()
                        }
                    }
                } else {
                    const modal = this.$refs.eventModal as HTMLDialogElement
                    if (modal) {
                        modal.close()
                        this.removeUrlParams()
                    }
                }
            })
        },

        async openModal() {
            this.modalOpen = true
        },

        closeModal() {
            this.modalOpen = false
        },

        async fetchEventData() {
            this.isLoading = true
            this.error = null

            // Create AbortController for timeout handling
            const controller = new AbortController()
            const timeoutId = setTimeout(() => controller.abort(), 10000) // 10 second timeout

            try {
                const response = await fetch(`/event-api/event/${this.eventSlug}`, {
                    signal: controller.signal,
                    // Additional options for better reliability
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                })

                // Clear timeout on successful response
                clearTimeout(timeoutId)

                if (!response.ok) {
                    if (response.status === 404) {
                        throw new Error('Dieser Termin wurde nicht gefunden')
                    } else if (response.status >= 500) {
                        throw new Error('Server-Fehler. Bitte versuchen Sie es später erneut')
                    } else {
                        throw new Error('Termindetails konnten nicht geladen werden')
                    }
                }

                const data = await response.json()

                if (data.success) {
                    this.eventData = data.event
                } else {
                    throw new Error(data.error || 'Termindetails konnten nicht geladen werden')
                }
            } catch (error) {
                // Clear timeout in case of error
                clearTimeout(timeoutId)

                console.error('Error fetching event data:', error)

                // Provide user-friendly German error messages based on error type and network status
                let userMessage = 'Termindetails konnten nicht geladen werden'

                if (error instanceof DOMException && error.name === 'AbortError') {
                    // Request was aborted (timeout)
                    userMessage = 'Zeitüberschreitung. Die Anfrage dauerte zu lange'
                } else if (error instanceof TypeError) {
                    // Network/connection issues (DNS, connection refused, etc.)
                    if (!navigator.onLine) {
                        userMessage = 'Keine Internetverbindung. Bitte überprüfen Sie Ihre Verbindung'
                    } else {
                        userMessage = 'Netzwerkfehler. Bitte versuchen Sie es erneut'
                    }
                } else if (error instanceof SyntaxError) {
                    // JSON parsing error
                    userMessage = 'Ungültige Serverantwort. Bitte versuchen Sie es erneut'
                }

                this.error = userMessage
            } finally {
                this.isLoading = false
            }
        },

        updateUrlParams() {
            if ('URLSearchParams' in window) {
                const url = new URL(window.location.href)
                url.searchParams.set('event', this.eventSlug)
                url.searchParams.delete('event-page-set')
                history.replaceState(null, '', url.toString())
            }
        },

        removeUrlParams() {
            const url = new URL(window.location.href)
            if (url.searchParams.get('event') === this.eventSlug) {
                url.searchParams.delete('event')
                url.searchParams.delete('event-page-set')
                history.replaceState(null, '', url.toString())
            }
        },

        formatDate(dateString: string, options: Intl.DateTimeFormatOptions): string {
            const date = new Date(dateString)
            return new Intl.DateTimeFormat('de-DE', options).format(date)
        },

        hasBlogpostOrTags() {
            return (this.eventData?.blogposts?.length ?? 0) > 0 || !!this.eventData?.tag?.page;
        }
    }
}

// Simple event card component (multiple instances on page)
export function eventCard(): AlpineComponent<{ eventSlug: string; openModal(): void }> {
    return {
        eventSlug: '',

        openModal() {
            // Dispatch global event for shared dialog
            const event = new CustomEvent('open-event-dialog', {
                detail: { slug: this.eventSlug }
            })
            document.dispatchEvent(event)
        }
    }
}

// For backwards compatibility, export the card component as default
export default eventCard
