import type { AlpineComponent } from 'alpinejs'

export function scrollToId(): AlpineComponent<{ scrollTo(targetId: string): void }> {
    return {
        scrollTo(targetId: string) {
            document.getElementById(targetId)?.scrollIntoView({ behavior: 'smooth', block: 'start' })
        }
    }
}

export default scrollToId
