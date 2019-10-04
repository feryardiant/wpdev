describe('my awesome website', () => {
  it('should do some chai assertions', async () => {
    await browser.url('/')
    const title = await browser.getTitle()

    title.should.to.be.equal('Local WordPress – Your slogan goes here')
  })
})
